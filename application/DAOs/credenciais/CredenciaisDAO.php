<?php

namespace DAOs\credenciais;

use DAOs\DAO;
use DateTime;
use model\credenciais\Credenciais;
use model\credenciais\CredenciaisCadastroAluno;
use model\credenciais\CredenciaisCadastroAdministrador;

use \PDO;
use Throwable;

class CredenciaisDAO extends DAO
{

    public function insert(Credenciais $model): Credenciais|bool
    {
        $query = "INSERT INTO credenciais (usuario, senha)
        VALUES (?, ?);";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $model->usuario);
        $stmt->bindValue(2, Credenciais::hashSenha($model->senha));
        return ($stmt->execute()) ? $this->get((int)$this->pdo->lastInsertId()) : false;
    }
    public function update(Credenciais $model): Credenciais|bool
    {
        $atual = $this->get($model->id);
        if ($atual === false) {
            return false;
        }
        $usuario = $model->usuario !== '' ? $model->usuario : $atual->usuario;
        $senha = $model->senha !== '' ? Credenciais::hashSenha($model->senha) : $atual->senha;
        $stmt = $this->pdo->prepare('UPDATE credenciais SET usuario = ?, senha = ? WHERE id = ?');
        return $stmt->execute([$usuario, $senha, $model->id]) ? $this->get($model->id) : false;
    }

    public function delete(int $id): bool
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('SELECT id FROM credenciais WHERE id = ? FOR UPDATE');
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() === false) {
                $this->pdo->rollBack();
                return false;
            }
            foreach (['noticia', 'evento', 'aviso'] as $tabela) {
                // A lista de tabelas é fixa, nunca vem da requisição.
                $stmt = $this->pdo->prepare("DELETE FROM $tabela WHERE id_administrador IN (SELECT id FROM administrador WHERE id_credenciais = ?) OR id_aluno_adm IN (SELECT id FROM aluno WHERE id_credenciais = ?)");
                $stmt->execute([$id, $id]);
            }
            foreach (['aluno', 'administrador'] as $tabela) {
                $this->pdo->prepare("DELETE FROM $tabela WHERE id_credenciais = ?")->execute([$id]);
            }
            $this->pdo->prepare('DELETE FROM credenciais WHERE id = ?')->execute([$id]);
            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function get(int $id): Credenciais|bool
    {
        $query = "SELECT * FROM credenciais WHERE id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $model = $stmt->fetchObject(Credenciais::class);

        return ($model !== false) ? $model : false;
    }
    public function getAll(): array
    {
        $query = "SELECT * FROM credenciais;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Credenciais::class);
    }
    public function logar(Credenciais $credencial): bool|Credenciais
    {
        $stmt = $this->pdo->prepare('SELECT * FROM credenciais WHERE usuario = ?');
        $stmt->execute([$credencial->usuario]);
        $obj = $stmt->fetchObject(Credenciais::class);
        $legado = $obj !== false && preg_match('/^[a-f0-9]{64}$/iD', $obj->senha);
        // Hash fictício evita uma resposta imediata quando o usuário não existe.
        $ficticio = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi';
        $valida = password_verify($credencial->senha, $obj !== false && !$legado ? $obj->senha : $ficticio);
        if ($legado) {
            $valida = hash_equals(strtolower($obj->senha), hash('sha256', $credencial->senha));
        }
        if ($obj === false || !$valida) {
            return false;
        }
        if (!$obj->ativo) {
            return $obj;
        }
        $algoritmo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;
        if ($legado || password_needs_rehash($obj->senha, $algoritmo)) {
            $hash = Credenciais::hashSenha($credencial->senha);
            $this->pdo->prepare('UPDATE credenciais SET senha = ? WHERE id = ?')->execute([$hash, $obj->id]);
        }
        $this->updateLastLogin($obj->id);
        return $this->get($obj->id);
    }

    public function adicionarFoto(Credenciais $obj): bool
    {
        try {
            $query = "UPDATE credenciais SET  foto = ? where id = ?;";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->foto, PDO::PARAM_STR);
            $stmt->bindValue(2, $obj->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Throwable $e) {
            return false;
        }
    }

    function updateLastLogin(int $id): bool
    {
        $time = new DateTime();
        $query = "UPDATE credenciais SET 
        last_login = ? where id = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1, $time->format("Y-m-d H:i:s"));
        $stmt->bindValue(2, $id);
        return $stmt->execute();
    }
    public function validarMatricula(?string $matricula, int $idCredencial = 0): void
    {
        if ($matricula === null || $matricula === '') {
            return;
        }
        // Serializa alterações de matrícula entre as duas tabelas de perfis.
        $this->pdo->query('SELECT id FROM tipo_adm ORDER BY id LIMIT 1 FOR UPDATE')->fetchColumn();
        $stmt = $this->pdo->prepare('SELECT id FROM aluno WHERE matricula = ? AND id_credenciais <> ? UNION ALL SELECT id FROM administrador WHERE matricula = ? AND id_credenciais <> ? LIMIT 1');
        $stmt->execute([$matricula, $idCredencial, $matricula, $idCredencial]);
        if ($stmt->fetchColumn() !== false) {
            throw new \InvalidArgumentException('Já existe uma conta com essa matrícula. Confira o número informado ou consulte a administração do portal.');
        }
    }

    function cadastroAluno(CredenciaisCadastroAluno $obj): bool
    {
        try {
            $this->pdo->beginTransaction();
            $this->validarMatricula($obj->matricula);
            $query = "INSERT INTO credenciais (usuario,senha) VALUES (?,?);";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->usuario);
            $stmt->bindValue(2, Credenciais::hashSenha($obj->senha));
            $stmt->execute();

            $idCredBuffer = $this->pdo->lastInsertId();

            $query = "INSERT INTO aluno (nome,matricula,data_nascimento,id_credenciais,id_serie) 
           VALUES (?,?,?,?,?);";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->nome);
            $stmt->bindValue(2, $obj->matricula);
            $stmt->bindValue(3, $obj->data_nascimento);
            $stmt->bindValue(4, $idCredBuffer, PDO::PARAM_INT);
            $stmt->bindValue(5, $obj->id_serie, PDO::PARAM_INT);


            $stmt->execute();

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            throw $e;
        }
    }

    function cadastroAdm(CredenciaisCadastroAdministrador $obj): bool
    {
        try {
            $this->pdo->beginTransaction();
            $this->validarMatricula($obj->matricula);
            $query = "INSERT INTO credenciais (usuario,senha) VALUES (?,?);";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->usuario);
            $stmt->bindValue(2, Credenciais::hashSenha($obj->senha));
            $stmt->execute();

            $idCredBuffer = $this->pdo->lastInsertId();

            $query = "INSERT INTO administrador (nome,matricula,id_credenciais,id_tipo_adm)
           VALUES (?,?,?,?);";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $obj->nome);
            $stmt->bindValue(2, $obj->matricula);
            $stmt->bindValue(3, $idCredBuffer, PDO::PARAM_INT);
            $stmt->bindValue(4, $obj->id_tipo_adm, PDO::PARAM_INT);


            $stmt->execute();

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            throw $e;
        }
    }

    function acesso(Credenciais $obj): bool
    {
        $id = $obj->id;
        $ativo = $obj->ativo;

        try {
            $this->pdo->beginTransaction();
            $query = "UPDATE credenciais set ativo = ? where id = ?;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $ativo, PDO::PARAM_BOOL);
            $stmt->bindValue(2, $id);
            $stmt->execute();
            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            throw $e;
        }
    }

    function buscarFotoAutorPubli(int $id): string|bool
    {
        try {
            $query = "SELECT foto from credenciais where id = ?;";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->execute();
            $foto = $stmt->fetchColumn();
            return is_string($foto) ? $foto : false;
        } catch (Throwable $e) {
            return false;
        }
    }
}
