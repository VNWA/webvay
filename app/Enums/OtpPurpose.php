<?php

namespace App\Enums;

enum OtpPurpose: string
{
    case Login = 'login';
    case ContractSigning = 'contract_signing';
}
