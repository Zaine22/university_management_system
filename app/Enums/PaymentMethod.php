<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case TwoPaymentMethod = 'twopaymentmethod';
    case Kpay = 'kpay';
    case KbzBanking = 'kbzbanking';
    case AyaBanking = 'ayabanking';
    case AyaPay = 'ayapay';
    case CbBanking = 'cbbanking';
    case CbPay = 'cbpay';
    case WaveMoney = 'wave_money';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::TwoPaymentMethod => 'twopaymentmethod',
            self::Kpay => 'Kpay',
            self::KbzBanking => 'KBZ Banking',
            self::AyaBanking => 'AYA Banking',
            self::AyaPay => 'AYA Pay',
            self::CbBanking => 'CB Banking',
            self::CbPay => 'CB Pay',
            self::WaveMoney => 'Wave Money',
        };
    }

    public static function filteredCases(bool $includeCash = false): array
    {
        return array_reduce(
            array_filter(
                self::cases(),
                fn ($method) => $method !== self::TwoPaymentMethod
                && ($includeCash || $method !== self::Cash) // Exclude Cash unless included
            ),
            fn ($carry, $method) => $carry + [$method->value => $method->label()],
            []
        );
    }

    /**
     * Get options including Cash.
     */
    public static function optionsIncludingCash(): array
    {
        return self::filteredCases(includeCash: true);
    }
}
