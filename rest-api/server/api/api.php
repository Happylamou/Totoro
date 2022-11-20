<?php function searchForId($id, $array) {
   foreach ($array as $key => $val) {
       if ($val['VIN'] === $id) {
           return $val['MEDIAID'];
       }
   }
   return null;
}

$array = array(
  0=> array(
    'CLIENTID' => 'GALLEY',
    'VIN' => 'WBAFDEG2317MCB73388',
    'MEDIAID' => '10011020061817-galley_082114-SDvcl-140880481613056500',
    'DEALERNAME' => 'Demo',
    'PUBLISHON' => '2014-08-28'
    ),
  1=> array(
    'CLIENTID' => 'GALLEY',
    'VIN' => 'WAULC68E74A053WE251',
    'MEDIAID' => '10011020061817-galley_082114-SDvcl-140880482109709900',
    'DEALERNAME' => 'Demo',
    'PUBLISHON' => '2014-08-26'
    ),
  2=> array(
    'CLIENTID' => 'GALLEY',
    'VIN' => 'WAULC68E74A053WE251',
    'MEDIAID' => '10011020061817-galley_082114-SDvcl-140880482109709900',
    'DEALERNAME' => 'Demo',
    'PUBLISHON' => '2014-08-26'
    ),    
  );

$search = searchForId('WAULC68E74A053WE251', $array);

var_dump($search);?>