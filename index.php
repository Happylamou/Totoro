<!DOCTYPE html>
<?php

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


foreach ($movie_data as $index => $movie) {
  $allmovies[$index]['poster'] = '<img src= "' . $movie->image . '" />';
  $allmovies[$index]['title'] = $movie->title;
  $allmovies[$index]['runtime'] = $movie->running_time;
  $allmovies[$index]['description'] = $movie->description;
}

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

<body>
  <form action="index.php" method="post">
    <table>
      <thead>
        <tr>
          <th>Poster</th>
          <th>Title</th>
          <th>Runtime</th>
          <th>Description</th>
        </tr>
        <tr>
          <td><input type="submit" value="click" name="submit"></td>
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
        <?php foreach ($allmovies as $row):
  array_map('htmlentities', $row); ?>
        <tr>
          <td class="info">
            <?php echo implode('</td><td class="info">', $row); ?>
          </td>
        </tr>
        <?php endforeach; ?>

      </tbody>
    </table>
  </form>
</body>



<?php
function display()
{

}

if (isset($_POST['submit'])) {
  display();
}
?>