<?php
session_start();

//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
$con = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
$per_page=5;

if(isset($_GET["page"]) )$start_page=$_GET["page"]*$per_page;
else $start_page=0;

//create connection
if(!$con){
    die("Connection mysql database fail!!".mysqli_connect_error());
}
// echo "Connect mysql successfully";
$sql="SELECT * FROM product";
$result = mysqli_query($con,$sql);
$numrow = mysqli_num_rows($result);
echo "<h1>The Sims Shop</h1>";
echo "มีจำนวนสินค้าทั้งหมด ".$numrow." รายการ<br>";
echo "หน้า ".($_GET["page"]+1)."/".ceil($numrow/$per_page)."<br>";
$prev = $_GET["page"]-1;
$next = $_GET["page"]+1;
if($prev ==-1){
    $prev=0;
}
if($next == (ceil($numrow/$per_page))){
    $next=ceil($numrow/$per_page)-1;
}

echo "<button onclick=location.href='https://shop2-6330300461.herokuapp.com/?page=$prev'>previous</button>";

for($i=0;$i<ceil($numrow/$per_page);$i++){
    echo "<a href='https://shop2-6330300461.herokuapp.com/?page=$i'>[".($i+1)."]</a>";
}

echo "<button onclick=location.href='https://shop2-6330300461.herokuapp.com/?page=$next'>next</button>";
$sql="SELECT * FROM product LIMIT $start_page,$per_page";
$result = mysqli_query($con,$sql);
if(mysqli_num_rows($result)>0){
    echo "<table border=1 borderColor ='#04d9ff'><tr><td>id</td><td>name</td><td>description</td><td>price</td></tr>";
    while($row=mysqli_fetch_assoc($result)){
        echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>";
        echo $row["description"]."</td><td>".$row["price"]."</td>";
        //echo "<td><a href='add_product.php?id=".$row["id"]."'>ใส่ตระก้า</td></tr>";
    }
    echo "</table>";
}else{
    echo "0results";
}

// if(isset($_SESSION["cart"])){
//     $total=0;
//     echo"<h1>ตระกร้าสินค้า</h1>";
//     echo "<table border=1 borderColor ='#04d9ff'><tr><th>ลำดับ</th><th>id</th><th>name</th><th>description</th><th>price</th></tr>";
//     for($i=0;$i<count($_SESSION['cart']);$i++){
//         $item =$_SESSION['cart'][$i];
//         echo "<tr><td>".($i+1)."</td>";
//         echo "<td>".$item['id']."</td>";
//         echo "<td>".$item['name']."</td>";
//         echo "<td>".$item['description']."</td>";
//         echo "<td>".$item['price']."</td>";
//         echo "<td><a href='del_cart.php?i=".$i."'>";
//         echo "<font color='red'>x</font></a></td></tr>";
//         $total+=$item['price'];
//     }
//     echo "</table>";
//     echo "<h1>ราคาสินค้า ".$total." บาท</h1>";
//     echo "<h2><a href='checkout.php'>สั่งซื้อ</h2>";
// }

mysqli_close($con);
?>
