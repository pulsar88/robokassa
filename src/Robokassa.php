<?php

namespace Fillincode\Robokassa;

use Illuminate\Support\Facades\Log;
use JsonException;

class Robokassa
{
    protected mixed $login = '';

    protected mixed $pass1 = '';

    protected mixed $pass2 = '';

    protected mixed $inv_id = '';

    protected mixed $inv_desc = '';

    protected mixed $out_sum = '';

    protected mixed $out_currency = '';

    protected mixed $is_test = '';

    protected mixed $receipt = '';

    protected mixed $urlencode_receipt;

    /**
     * @throws JsonException
     */
    public function __construct($inv_id = null, $inv_desc = null, $sum = null, $currency = null)
    {
        $this->login = config('robokassa.login', '');
        $this->is_test = config('robokassa.is_test', false);
        if ($this->is_test) {
            $this->pass1 = config('robokassa.test_pass_1', '');
            $this->pass2 = config('robokassa.test_pass_2', '');
        } else {
            $this->pass1 = config('robokassa.pass_1', '');
            $this->pass2 = config('robokassa.pass_2', '');
        }

        $this->setInvId($inv_id);
        $this->setInvDesc($inv_desc);
        $this->setOutSumm($sum);
        $this->setOutCurrency($currency);
        $this->setReceipt((int) $sum);

        $debug = [];
        $debug['inv_id'] = $inv_id;
        $debug['inv_desc'] = $inv_desc;
        $debug['sum'] = $sum;

        Log::channel(config('robokassa.log_driver'))->debug('New robokassa obj:');
        Log::channel(config('robokassa.log_driver'))->debug('debug', $debug);
    }

    public function setInvId(?string $inv_id): void
    {
        if (! is_null($inv_id)) {
            $this->inv_id = $inv_id;
        }
    }

    public function setInvDesc(?string $inv_desc): void
    {
        if (! is_null($inv_desc)) {
            $this->inv_desc = $inv_desc;
        }
    }

    public function setOutSumm(?string $out_sum): void
    {
        if (! is_null($out_sum)) {
            $this->out_sum = $out_sum;
        }
    }

    public function setOutCurrency(?string $currency): void
    {
        if (! in_array($currency, ['EUR', 'USD'])) {
            $currency = '';
        }
        if (! is_null($currency)) {
            $this->out_currency = $currency;
        }
    }

    /**
     * @throws JsonException
     */
    public function setReceipt(int $out_sum = 0): void
    {
        $this->receipt = json_encode([
            'sno' => 'usn_income',
            'items' => [
                [
                    'name' => 'Пополнение счета',
                    'quantity' => 1,
                    'sum' => $out_sum,
                    'payment_method' => 'full_payment',
                    'payment_object' => 'payment',
                    'tax' => 'none',
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $this->urlencode_receipt = urlencode($this->receipt);
    }

    private function _getCrc(): ?string
    {
        $string = "$this->login:$this->out_sum:$this->inv_id:".
            //    (empty($this->out_currency) ? '' : ":$this->out_currency") .
            // ":$this->receipt" .
            "$this->pass1";

        $md5 = md5($string);

        Log::channel('robokassa')->debug('crc', [
            'string' => $string,
            'md5' => $md5,
        ]);

        return $md5;
    }

    public function getLink(): ?string
    {
        $crc = $this->_getCrc();
        $link = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$this->login&OutSum=$this->out_sum".
            //(empty($this->out_currency) ? '' : "&OutSumCurrency=$this->out_currency") .
            "&InvId=$this->inv_id&".
            //"Receipt=$this->urlencode_receipt&" .
            "Desc=$this->inv_desc&SignatureValue=$crc".($this->is_test ? '&IsTest=1' : '');
        Log::channel('robokassa')->debug('Generated link: '.$link);

        return $link;
    }

    public function checkResultCRC($in_crc): bool|string|null
    {
        $in_crc = strtoupper($in_crc);

        // build own CRC
        $check_crc = strtoupper(md5(
            "$this->out_sum:$this->inv_id".//:{$this->receipt}".
            //  (empty($this->out_currency) ? '' : ":$this->out_currency") .
            ":$this->pass2"
        ));

        Log::channel('robokassa')->debug('check', [
            'in_crc' => $in_crc,
            'check_crc' => $check_crc,
            'string' => "$this->out_sum:$this->inv_id".//:{$this->receipt}".
                //  (empty($this->out_currency) ? '' : ":$this->out_currency") .
                ":$this->pass2",
        ]);

        return $check_crc === $in_crc;
    }

    public function checkSuccessCRC($in_crc): bool|string|null
    {
        $in_crc = strtoupper($in_crc);

        // build own CRC
        $check_crc = strtoupper(md5(
            "$this->out_sum:$this->inv_id".//:{$this->receipt}".
            // (empty($this->out_currency) ? '' : ":$this->out_currency") .
            ":$this->pass1"
        ));

        return $check_crc === $in_crc;
    }
}
