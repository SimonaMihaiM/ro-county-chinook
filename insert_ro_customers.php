<?php

$database = new \PDO("sqlite:chinook.sqlite");

// src: https://en.wikipedia.org/wiki/ISO_3166-2:RO
$counties = [
    "AB" => "Alba",
    "AR" => "Arad",
    "AG" => "Argeș",
    "BC" => "Bacău",
    "BH" => "Bihor",
    "BN" => "Bistrița-Năsăud",
    "BT" => "Botoșani",
    "BV" => "Brașov",
    "BR" => "Brăila",
    "BZ" => "Buzău",
    "CS" => "Caraș-Severin",
    "CL" => "Călărași",
    "CJ" => "Cluj",
    "CT" => "Constanța",
    "CV" => "Covasna",
    "DB" => "Dâmbovița",
    "DJ" => "Dolj",
    "GL" => "Galați",
    "GR" => "Giurgiu",
    "GJ" => "Gorj",
    "HR" => "Harghita",
    "HD" => "Hunedoara",
    "IL" => "Ialomița",
    "IS" => "Iași",
    "IF" => "Ilfov",
    "MM" => "Maramureș",
    "MH" => "Mehedinți",
    "MS" => "Mureș",
    "NT" => "Neamț",
    "OT" => "Olt",
    "PH" => "Prahova",
    "SM" => "Satu Mare",
    "SJ" => "Sălaj",
    "SB" => "Sibiu",
    "SV" => "Suceava",
    "TR" => "Teleorman",
    "TM" => "Timiș",
    "TL" => "Tulcea",
    "VS" => "Vaslui",
    "VL" => "Vâlcea",
    "VN" => "Vrancea",
    "B" => "București"
];
// src: https://en.wikipedia.org/wiki/List_of_most_common_surnames_in_Europe#Romania
$surnames = ["Popa", "Popescu", "Pop", "Radu", "Dumitru", "Stan", "Stoica", "Gheorghe", "Matei", "Ciobanu", "Ionescu", "Rusu"];
// src: https://en.wikipedia.org/wiki/Romanian_name
$firstnames = ["Andrei", "Constantin", "Cristian", "Daniel", "Dan", "Gheorghe", "George", "Grigore", "Ilie", "Ion", "Ioan", "Iacob", "Laurențiu", "Luca", "Marcu", "Matei", "Mihail", "Mihai", "Nicolae", "Niculaie", "Pavel", "Paul", "Petru", "Petre", "Ștefan", "Vasile.Themostcommonname", "Maria", "Mariana", "Marioara", "Maricica", "Maricela", "Măriuca", "Mara", "Marina", "Marilena", "Marieta", "Marinela", "Marisa", "Marița", "Marusia", "Mia", "Mioara", "Traian", "Titus", "Marius", "Octavian", "Ovidiu", "Aurel", "Cornel"];



function generatePostalCode()
{
    return rand(100000, 900000);
}

function generatePhone()
{
    return "+407".rand(2,6).rand(1000000,9999999);
}

function generateEmail($firstName, $lastName)
{
    return strtolower($firstName).".".strtolower($lastName)."@gmail.com";
}

function generateAddress($surnames, $firstnames)
{
    $type = ['Strada', 'Bulevard', 'Aleea'];
    return $type[rand(0,2)] . " ". $surnames[rand(0, count($surnames)-1)] ." ". $firstnames[rand(0, count($firstnames)-1)] . ", nr. ". rand(1, 100);
}

$lastCustomerId = $database->query("SELECT MAX(CustomerId) FROM Customer")->fetch(\PDO::FETCH_COLUMN);



// INSERT Customers.
$insertC = $database->prepare("INSERT INTO Customer(CustomerId, FirstName, LastName, Address, City, State, Country, PostalCode, Phone, Email, SupportRepId) VALUES (:CustomerId, :FirstName, :LastName, :Address, :City, :State, :Country, :PostalCode, :Phone, :Email, :SupportRepId)");


for ($i=1; $i<=100; $i++) {
    $lastName =  $surnames[array_rand($surnames)];
    $firstName =  $firstnames[array_rand($firstnames)];
    $employeeNo = rand(3,5);
    $state = array_rand($counties);
    $city = $counties[$state];
    $insertC->execute([
        ':CustomerId' => $lastCustomerId + $i,
        ':FirstName' => $firstName,
        ':LastName' => $lastName,
        ':Address'=>generateAddress($surnames, $firstnames),
        ':City'=>$city,
        ':State'=>$state,
        ':Country'=>"Romania",
        ':PostalCode'=>generatePostalCode(),
        ':Phone'=>generatePhone(),
        ':Email'=>generateEmail($firstName, $lastName),
        ':SupportRepId'=>$employeeNo
    ]);
}
