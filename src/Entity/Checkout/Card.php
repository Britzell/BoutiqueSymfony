<?php


namespace App\Entity\Checkout;


class Card
{
    protected $number;
    protected $exp_month;
    protected $exp_year;
    protected $cvc;

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number): void
    {
        $this->number = $number;
    }

    public function getExpMonth()
    {
        return $this->exp_month;
    }

    public function setExpMonth($exp_month): void
    {
        $this->exp_month = $exp_month;
    }

    public function getExpYear()
    {
        return $this->exp_year;
    }

    public function setExpYear($exp_year): void
    {
        $this->exp_year = $exp_year;
    }

    public function getCvc()
    {
        return $this->cvc;
    }

    public function setCvc($cvc): void
    {
        $this->cvc = $cvc;
    }

    public function hydrate(array $data)
    {
        if (isset($data['number']))
            $this->setNumber($data['number']);
        if (isset($data['exp_month']))
            $this->setExpMonth($data['exp_month']);
        if (isset($data['exp_year']))
            $this->setExpYear($data['exp_year']);
        if (isset($data['cvc']))
            $this->setCvc($data['cvc']);
    }

    public function getData(): array
    {
        $data = [];
        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }
        return $data;
    }
}
