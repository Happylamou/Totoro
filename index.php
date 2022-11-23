<!DOCTYPE html>
<?php
//$results = SearchFun($inputtit);

$inputtit = "";
$inputlen = 0;
$inputsym = "";
if (isset($_GET['submit'])) {
  $inputtit = htmlentities($_GET['mname']);
  $inputlen = htmlentities($_GET['mlength']);
  $inputsym = htmlentities($_GET['symbols']);
  $inputres = SearchFun($inputtit, $inputlen, $inputsym);
  //echo $inputlen;
  //echo $inputtit;
}


$results = SearchFun($inputtit, $inputlen, $inputsym);

function SearchFun($inputtit, $inputlen, $inputsym)
{

  $allmovies = array();
  $curl_handle = curl_init();
  $url = "https://ghibliapi.herokuapp.com/films?limit=200";

  curl_setopt($curl_handle, CURLOPT_URL, $url);
  curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl_handle, CURLOPT_BINARYTRANSFER, true);
  $curl_data = curl_exec($curl_handle);
  curl_close($curl_handle);
  $response_data = json_decode($curl_data);
  $movie_data = $response_data;

  $i = 0;
  foreach ($movie_data as $movie) {
    if (stripos($movie->title, $inputtit) or (stripos($movie->title, $inputtit)) === 0) {

      if ($inputlen === "" or $inputlen === $movie->running_time) {

      } elseif ($inputsym === "1") {
        if (intval($inputlen) > intval(trim($movie->running_time))) {
          $allmovies[$i]['poster'] = '<img src= "' . $movie->image . '" />';
          $allmovies[$i]['title'] = $movie->title;
          $allmovies[$i]['runtime'] = $movie->running_time;
          $allmovies[$i]['description'] = $movie->description;
          $i = $i + 1;
          echo "got less";
        }
      } elseif ($inputsym === "2") {
        if (intval($inputlen) < intval($movie->running_time)) {
          $allmovies[$i]['poster'] = '<img src= "' . $movie->image . '" />';
          $allmovies[$i]['title'] = $movie->title;
          $allmovies[$i]['runtime'] = $movie->running_time;
          $allmovies[$i]['description'] = $movie->description;
          $i = $i + 1;
          echo "got more";
        }
      }

    }
    else{      $allmovies[$i]['poster'] = '<img src= "' . $movie->image . '" />';
      $allmovies[$i]['title'] = $movie->title;
      $allmovies[$i]['runtime'] = $movie->running_time;
      $allmovies[$i]['description'] = $movie->description;
      $i = $i + 1;
      echo "got text";}
  }
  return $allmovies;
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
  <form action="index.php" method="get">
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
            <input type="text" placeholder="Movie title" id="mname" name="mname">
          </td>
          <td>
            <input type="number" step="1" placeholder="Runtime" id="mlength" name="mlength">
            <select name="symbols" id="symbols">
              <option value="" id="none" name="none">=</option>
              <option value="1" id="less" name="less">less n></option>
              <option value="2" id="more" name="more">more n<</option>
            </select>
          </td>
          <td></td>

        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $row):
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