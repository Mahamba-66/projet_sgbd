<?php

namespace App\Imports;

use App\Models\EligibleVoter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VotersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new EligibleVoter([
            'first_name' => $row['prenom'],
            'last_name'  => $row['nom'],
            'card_number' => $row['numero_de_carte']
        ]);
    }
}
