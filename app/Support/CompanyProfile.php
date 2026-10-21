<?php

namespace App\Support;

use App\Models\Setting;

class CompanyProfile
{
    public static function name(): string
    {
        $name = trim((string) Setting::getValue('company.name', ''));

        if ($name !== '') {
            return $name;
        }

        return (string) config('bargain.company_name', config('app.name'));
    }

    /**
     * Address and contact lines stored from Company Information.
     *
     * @return list<string>
     */
    public static function lines(): array
    {
        $lines = [];
        $legal = trim((string) Setting::getValue('company.legal_name', ''));
        if ($legal !== '' && $legal !== self::name()) {
            $lines[] = $legal;
        }

        foreach (['address1', 'address2'] as $key) {
            $value = trim((string) Setting::getValue('company.'.$key, ''));
            if ($value !== '') {
                $lines[] = $value;
            }
        }

        $city = trim((string) Setting::getValue('company.city', ''));
        $state = trim((string) Setting::getValue('company.state', ''));
        $zip = trim((string) Setting::getValue('company.zip', ''));
        $cityLine = trim($city.($city !== '' && $state !== '' ? ', ' : '').$state.($zip !== '' ? ' '.$zip : ''));
        if ($cityLine !== '') {
            $lines[] = $cityLine;
        }

        $country = trim((string) Setting::getValue('company.country', ''));
        if ($country !== '' && strcasecmp($country, 'USA') !== 0) {
            $lines[] = $country;
        }

        foreach (['phone' => 'Phone', 'fax' => 'Fax', 'email' => 'Email', 'website' => 'Web'] as $key => $label) {
            $value = trim((string) Setting::getValue('company.'.$key, ''));
            if ($value !== '') {
                $lines[] = $label.': '.$value;
            }
        }

        $ein = trim((string) Setting::getValue('company.federal_ein', ''));
        if ($ein !== '') {
            $lines[] = 'EIN: '.$ein;
        }

        return $lines;
    }
}
