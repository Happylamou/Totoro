<!DOCTYPE html>
<?php
//$results = SearchFun($inputtit);
//==========================
//Current problems, couldn't get returning to work, couldn't figure out why it runs twice
//the search on the other hand does find the correct movies, by either filtering with one or two filters
//==========================
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


  foreach ($movie_data as $movie) {
    $textchk = stripos($movie->title, $inputtit);
    if ($inputtit === "") {
      if ($inputlen !== "") {
        runtimesearch($inputlen, $inputsym, $movie);
      }
      echo "Empty txt";
      //return $allmovies;
    } elseif ($textchk or $textchk === 0) {
      if ($inputlen !== "") {
        runtimesearch($inputlen, $inputsym, $movie);
      }
      echo "TEXT MATCH";
      //return $allmovies;
    } elseif ($textchk === false) {
      runtimesearch($inputlen, $inputsym, $movie);
      echo "no text matches";
      //return $allmovies;
    }
  }

  return $allmovies;
}

function runtimesearch($inputlen, $inputsym, $movie)
{
  $movielen = array();
  $j = count($movielen);
  if ($inputsym === "1") {
    if (intval($inputlen) > intval(trim($movie->running_time))) {
      $movielen[$j]['poster'] = '<img src= "' . $movie->image . '" />';
      $movielen[$j]['title'] = $movie->title;
      $movielen[$j]['runtime'] = $movie->running_time;
      $movielen[$j]['description'] = $movie->description;
      //$j = $j + 1;
      echo "got less";
      //var_dump($movielen);
    }
  } elseif ($inputsym === "2") {
    if (intval($inputlen) < intval($movie->running_time)) {
      $movielen[$j]['poster'] = '<img src= "' . $movie->image . '" />';
      $movielen[$j]['title'] = $movie->title;
      $movielen[$j]['runtime'] = $movie->running_time;
      $movielen[$j]['description'] = $movie->description;
      //$j = $j + 1;
      echo "got more";
    }
  } else {
    $movielen[$j]['poster'] = '<img src= "' . $movie->image . '" />';
    $movielen[$j]['title'] = $movie->title;
    $movielen[$j]['runtime'] = $movie->running_time;
    $movielen[$j]['description'] = $movie->description;

    echo "|neither|";
  }
  $j = $j + 1;
  echo "$j";
  var_dump($movielen);
  return $movielen;
}

function titlesearch($inputtit, $movie_data)
{
  foreach ($movie_data as $movie) {
    if (stripos($movie->title, $inputtit) or (stripos($movie->title, $inputtit)) === 0) {

    } elseif ($inputtit === "") {

    } else {

    }
  }
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
  <form action="api.php" method="get">
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