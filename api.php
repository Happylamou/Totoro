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
  $results = SearchFun($inputtit, $inputlen, $inputsym);
}

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
      if ($inputlen !== "") {
        $matchRuntime = runtimesearch($inputlen, $inputsym, $movie);
		if (!$matchRuntime) continue;
      }

	 if (!empty($inputtit) and stripos($movie->title, $inputtit) === false ) continue;
	
	$allmovies[] = $movie;
  }

  return $allmovies;
}

function runtimesearch($inputlen, $inputsym, $movie)
{
  if ($inputsym === "1") {
    return (intval($inputlen) > intval(trim($movie->running_time)));
  } elseif ($inputsym === "2") {
  	return (intval($inputlen) < intval($movie->running_time));
  } else {
   return intval($inputlen) == intval(trim($movie->running_time));
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
        <?php foreach ($results as $row) {
			echo "<tr>
				<td><img src='".$row->image."' /></td>
				<td>".$row->title."</td>
				<td>".$row->running_time."</td>
				<td>".$row->description."</td>
			</tr>";
		}
		?>
      </tbody>
    </table>
  </form>
</body>