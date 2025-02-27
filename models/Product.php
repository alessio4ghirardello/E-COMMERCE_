<?php

require_once __DIR__ . "/../connection/DbManager.php";
//includono dbmanager che si trova della directory superiore   ".." rispetto al percorso corrente "__DIR__"


class Product
{
    private $id;
    private $nome;
    private $prezzo;
    private $marca;


    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getPrezzo()
    {
        return $this->prezzo;
    }

    public function setPrezzo($prezzo)
    {
        $this->prezzo = $prezzo;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    public static function Find($id)
    {
        $pdo = self::Connect();
        $stmt = $pdo->prepare("select * from ecommerce.products where id = :id");
        $stmt->bindParam(":id", $id);
        if ($stmt->execute()) {
            return $stmt->fetchObject("Product");
        } else {
            throw new PDOException("Prodotto non trovato");
        }
    }

    public static function Create($params)
    {
        $pdo = self::Connect();
        $stmt = $pdo->prepare("insert into ecommerce.products (nome,prezzo,marca) values (:nome,:prezzo,:marca)");
        $stmt->bindParam(":nome", $params["nome"]);
        $stmt->bindParam(":prezzo", $params["prezzo"]);
        $stmt->bindParam(":marca", $params["marca"]);
        if ($stmt->execute()) {
            $stmt = $pdo->prepare("select * from ecommerce.products order by id desc limit 1");
            $stmt->execute();
            return $stmt->fetchObject("Product");
        } else {
            throw new PDOException("Errore Nella Creazione");
        }
    }

    public static function FetchAll()
    {
        $pdo = self::Connect();
        $stmt = $pdo->query("select * from ecommerce.products");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');

    }

    public function Update($params) {
        $pdo = self::Connect();
        $stmt = $pdo->prepare("UPDATE ecommerce.products SET nome = :nome, prezzo = :prezzo WHERE id = :id");
        $stmt->bindParam(":nome", $params["nome"]);
        $stmt->bindParam(":prezzo", $params["prezzo"]);
        $stmt->bindParam(":id", $params["id"]);
        if ($stmt->execute()) {
            return true;
        } else {
            throw new PDOException("Errore nell'aggiornamento del prodotto");
        }
    }

    public function Delete($id) {
        $pdo = self::Connect();
        $stmt = $pdo->prepare("DELETE FROM ecommerce.products WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new PDOException("Errore nell'eliminazione del prodotto");
            }
        } catch (PDOException $e) {
            throw new PDOException("Errore nell'eliminazione del prodotto: " . $e->getMessage());
        }
    }




    public static function Connect()
    {
        return DbManager::Connect("ecommerce");
    }


}