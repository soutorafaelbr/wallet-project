<?php

namespace Domain\Document\Enum;

enum DocumentTypeEnum: string
{
    case CPF = 'CPF';
    case CNPJ = 'CNPJ';
}
