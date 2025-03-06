<?php namespace App\Imports;

use App\Models\EligibleVoter;
use Maatwebsite\Excel\Concerns\ToModel;

class VotersImport implements ToModel
{
    public function model(array $row): EligibleVoter
    {
        return new EligibleVoter([
            'first_name'  => $row['prenom'],
            'last_name'   => $row['nom'],
            'card_number' => $row['numero_de_carte']
        ]);
    }
}
?>