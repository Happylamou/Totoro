<!DOCTYPE html>
<?php


?>

<style>
  table {
    empty-cells: show
  }

  img {
    width: 200px
  }

  td {
    border: 1px solid black
  }

  thead {
    background-color: orange
  }
</style>


<form action="testing.php" method="get">
  <table>
    <thead>
      <tr>
        <th>Poster</th>
        <th>Title</th>
        <th>Runtime</th>
        <th>Description</th>
      </tr>
      <tr>
        <td><input type="submit" name="submit"></td>
        <td>
          <input type="text" placeholder="Movie title" id="moviename" name="mname">
        </td>
        <td>
          <input type="number" step="1" placeholder="Runtime" id="movielength" name="mlength">
        </td>
        <td></td>

      </tr>
    </thead>
    <tbody>

      <body>

      </body>

    </tbody>
  </table>
</form>




<?php







function SearchTitle()
{

  $allmovies = array();
  // Initiate curl session in a variable (resource)
  $curl_handle = curl_init();

  $url = "https://ghibliapi.herokuapp.com/films?limit=200";

  // Set the curl URL option
  curl_setopt($curl_handle, CURLOPT_URL, $url);

  // This option will return data as a string instead of direct output
  curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl_handle, CURLOPT_BINARYTRANSFER, true);

  // Execute curl & store data in a variable
  $curl_data = curl_exec($curl_handle);
  //$image = curl_exec($curl_handle);


  curl_close($curl_handle);

  // Decode JSON into PHP array
  $response_data = json_decode($curl_data);

  // All movie data exists in 'data' object
  $movie_data = $response_data;

  $search_text = 'mname';
  foreach ($movie_data as $index => $movie) {
    $allmovies[$index]['poster'] = '<img src= "' . $movie->image . '" />';
    $allmovies[$index]['title'] = $movie->title;
    $allmovies[$index]['runtime'] = $movie->running_time;
    $allmovies[$index]['description'] = $movie->description;
  }
$results = array();
foreach($allmovies as $key => $array) {
  foreach($array as $value) {
      if(strpos($value, $_GET['mname']) !== false) {
         echo $key;
         echo $value;
         $results=$allmovies[$key];
         var_dump($results);
      }
  }
}

foreach( $results as $result )
{
    echo '<tr>';
    foreach( $result as $key )
    {
        echo '<td>'.$key.'</td>';
    }
    echo '</tr>';
}
}

//<?php foreach ($allmovies as $row):
//  array_map('htmlentities', $row); ?/>
//        <tr>
//          <td class="info">
//            <?php echo implode('</td><td class="info">', $row); ?/>
//          </td>
//        </tr>
//        </?php endforeach; ?/>
//-------------------------------------------------

  //$suppliers = array();
  //$customers = array();
  
  //foreach ($allmovies as $rkey){
  //
  //    if (strpos($allmovies[$rkey]['title'],$_GET('mname'))){
  //
  //        $customers[] = $allmovies;
  //        var_dump ($customers);
  //
  //    }
  //}
//------------
//  foreach ($allmovies as $key => $val) {
//
//    array_filter(
//      $allmovies,
//      function ($el) use ($search_text) {
//        return (strpos($el['title'], $search_text) !== false);
//
//      }
//    );
//  }
//}


if ($_GET) {
  if (isset($_GET['submit'])) {
    SearchTitle();
  }
}