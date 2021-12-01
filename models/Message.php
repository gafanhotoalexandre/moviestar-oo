<?php

class Message
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Define mensagem a ser apresentada e
     * redireciona o usuário para a página devida
     * 
     * @param string $msg
     * @param string $type
     * @param string $redirectTo
     * 
     * @return void
     */
    public function setMessage(string $msg, string $type, string $redirectTo = 'index.php')
    {
        $_SESSION['msg'] = $msg;
        $_SESSION['type'] = $type;

        // Enviando usuário para página index
        if ($redirectTo != 'back') {
            header('Location: '. $this->url . $redirectTo);
            exit;
        }
        // Enviando usuário para página anterior
        header('Location: '. $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Retorna mensgem e tipo de erro, caso haja
     * ou retorna false
     * 
     * @return array|bool
     */
    public function getMessage()
    {
        if (empty($_SESSION['msg'])) {
            return false;
        }

        return [
            'msg' => $_SESSION['msg'],
            'type' => $_SESSION['type']
        ];
    }

    /**
     * Limpa SESSION de mensagens
     * 
     * @return void
     */
    public function clearMessage(): void
    {
        $_SESSION['msg'] = '';
        $_SESSION['type'] = '';
    }
}
