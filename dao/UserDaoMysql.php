<?php

require_once './models/User.php';
require_once './models/Message.php';

class UserDaoMysql implements UserDaoInterface
{
    private PDO $conn;
    private string $url;
    private Message $message;

    public function __construct(PDO $conn, $url)
    {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    /**
     * Constrói um objeto de User
     * 
     * @param array $data
     * 
     * @return User $user
     */
    public function buildUser(array $data): User
    {
        $user = new User();

        $user->id = $data['id'];
        $user->name = $data['name'] ?? '';
        $user->lastname = $data['lastname'] ?? '';
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->image = $data['image'] ?? '';
        $user->bio = $data['bio'] ?? '';
        $user->token = $data['token'];

        return $user;
    }

    public function create(User $user, bool $authUser = false)
    {
        $stmt = $this->conn->prepare('INSERT INTO users(
            name, lastname, email, password, token
        ) VALUES (
            :name, :lastname, :email, :password, :token
        )');

        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':lastname', $user->lastname);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':token', $user->token);

        $stmt->execute();

        // AUTENTICAR USUÁRIO CASO $authUser SEJA TRUE
        if ($authUser) {
            $this->setTokenToSession($user->token);
        }
    }

    public function update(User $user, bool $redirect = true)
    {
        $stmt = $this->conn->prepare('UPDATE users SET 
            name = :name,
            lastname = :lastname,
            email = :email,
            token = :token,
            image = :image,
            bio = :bio
            WHERE id = :id');

        $stmt->bindParam(':id', $user->id);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':lastname', $user->lastname);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':token', $user->token);
        $stmt->bindParam(':image', $user->image);
        $stmt->bindParam(':bio', $user->bio);
        $stmt->execute();

        if ($redirect) {
            $this->message->setMessage(
                'Dados atualizados com sucesso!',
                'success',
                'editprofile.php'
            );
        }
    }

    /**
     * PASSÍVEL DE REFACTOR
     */
    public function verifyToken(bool $protected = false)
    {
        if (!empty($_SESSION['token'])) {
            // Pegar o token da session e checar existência do usuário
            $token = $_SESSION['token'];
            $user = $this->findByToken($token);
    
            if (! $user) {
                $this->message->setMessage(
                    'Faça a autenticação para acessar esta página',
                    'error',
                    'index.php'
                );
                exit;        
            }
            return $user;

        } else if ($protected) {
            $this->message->setMessage(
                'Faça a autenticação para acessar esta página',
                'error',
                'index.php'
            );
            exit;    
        }
    }

    public function setTokenToSession(string $token, bool $redirect = true)
    {
        // Salvar token na session
        $_SESSION['token'] = $token;
        if ($redirect) {
            // Redirecionando para perfil do usuário
            $this->message->setMessage(
                'Seja bem-vindo!',
                'success',
                'editprofile.php'
            );
        }
    }

    /**
     * Verifica se os dados enviados correspondem à um
     * usuário no banco. Retornando true ou false
     * 
     * @param string $email
     * @param string $password
     * 
     * @return bool
     */
    public function authenticateUser(string $email, string $password)
    {
        $user = $this->findByEmail($email);

        if ($user) {            
            if (password_verify($password, $user->password)) {
                // Gerar token e inserir na session
                $token = $user->generateToken();
                $this->setTokenToSession($token, false);

                // Atualizar token do usuário
                $user->token = $token;
                $this->update($user, false);

                return true;
            }
        }
        return false;
    }

    /**
     * Retorna true para usuário existente e false
     * para usuário inexistente
     * 
     * @param string $email
     * 
     * @return bool
     */
    public function emailExists(string $email)
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // E-mail já existente
        if ($stmt->rowCount() > 0) {
            return true;
        }
        // E-mail ainda não cadastrado
        return false;
    }

    public function findById(int $id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return false;
        }

        $data = $stmt->fetch( PDO::FETCH_ASSOC );
        $user = $this->buildUser($data);

        return $user;
    }

    /**
     * Realiza Select, via e-mail, e retorna um objeto User
     * 
     * @param string $email
     * 
     * @return object|bool
     */
    public function findByEmail(string $email)
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return false;
        }

        $data = $stmt->fetch( PDO::FETCH_ASSOC );
        $user = $this->buildUser($data);

        return $user;
    }

    /**
     * Realiza Select, via token, e retorna um objeto User
     * 
     * @param string $token
     * 
     * @return object|bool
     */
    public function findByToken(string $token)
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE token = :token');
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if (! ($stmt->rowCount() > 0)) {
            return false;
        }

        $data = $stmt->fetch( PDO::FETCH_ASSOC );
        $user = $this->buildUser($data);
        
        return $user;
    }

    /**
     * Destrói o token e redireciona o usuário à index
     * 
     * @return void
     */
    public function destroyToken(): void
    {
        // Removendo token da session
        $_SESSION['token'] = '';

        // Redirecionando o usuário
        $this->message->setMessage('Você fez o logout com sucesso!', 'success');
    }

    public function changePassword(User $user)
    {
        $stmt = $this->conn->prepare('UPDATE users SET
            password = :password
            WHERE id = :id
        ');

        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':id', $user->id);
        $stmt->execute();

        // Redirecionando usuário
        $this->message->setMessage(
            'Senha alterada com sucesso!',
            'success',
            'editprofile.php'
        );
    }


}
