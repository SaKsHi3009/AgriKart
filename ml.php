<?php

// require_once "lib/accessory/functions.php";

// require_once "lib/accessory/matrix.php";
// require_once "lib/accessory/scaling.php";
// require_once "lib/parametric/regression.php";
// //require_once "lib/parametric/neural_network.php";
// require_once "lib/parametric/anomaly_detection.php";
// require_once "lib/parametric/naivebayes.php";
// require_once "lib/parametric/sann.php";
// require_once "lib/unsupervised/kmeans.php";
// require_once "lib/unsupervised/knn.php";

use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
//    $classifier=new SVC(Kernel:Linear,$cost=1000);
//    $classifier=new SVC(Kernel:RBF,$cost=1000,$degree=3,$gamma=6);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agrikartdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$connection = mysqli_connect($servername, $username, $password, $dbname);
$query = "SELECT * FROM `products`";

// Execute the query and store the result set
$results = mysqli_query($connection, $query);
$classifier = new SVC(kernel::LINEAR, $cost = 1000);
$i=0;$j=0;
foreach($results as $rows){
    if($j==4){
        $j=0;$i++;
    }
    $samples[$i][$j++]=$rows['name'];
    $samples[$i][$j++]=$rows['id'];
    $samples[$i][$j++]=$rows['category'];
    $samples[$i][$j++]=$rows['price'];
}
$query1 = "SELECT DISTINCT season FROM 'products'";
$res=mysqli_query($conn,$query1);
$l=0;
foreach($res as $rows){
    $labels[$l++]=$rows['season'];
}
$dbhost = 'localhost';
$dbname = 'agrikartdb';
$dbuser = 'root';
$dbpass = '';


try {
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Connection error :" . $exception->getMessage();
}
$classifier->train($samples, $labels);
$statement = $pdo->prepare("SELECT  * FROM products where category LIKE ? or name LIKE ?");
$statement->execute(array($search_text,$search_text));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$i = 0;
$j = 0;
foreach ($result as $rows) {
    if ($j == 4) {
        $j = 0;
        $i++;
    }
    $arr[$i][$j++] = $rows['name'];
    $arr[$i][$j++] = $rows['id'];
    $arr[$i][$j++] = $rows['category'];
    $arr[$i][$j++] = $rows['price'];}
$sea=$classifier->predict($arr);

