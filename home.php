<?php
session_start();
$PageTitle = 'Home';
include "init.php";
?>

<section id="body">
    <div class="container">
        <div class="row d-flex flex-row p-5">
        <?php
        $stmt = $con->prepare("SELECT * FROM items");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if($rows > 0){
               foreach($rows as $item){?>
                    <div class="col-sm-6 col-md-3 ">
                        <div class="thumbnail">
                            <img src="layout/images/beautiful-beauty-beauty-model-2661256.jpg" alt="img" width="100%">
                            <div class="card-body bg-danger text-dark">
                            <h5 ><?php echo $item["Name"].' - '.$item["Price"] ?></h5>
                            <h5 ><?php echo $item["Status"] ?></h5>
                            </div>
                            <div class="card-body bg-dark">
                            <a href="#" class="btn btn-danger">view</a>
                            <a href="#" class="btn btn-danger">buy</a>
                        </div>
                    </div>
               <?php }
            }?>
       </div>
                
        </div>
    </div>
</section>



<?php
include $tpl.'footer.php';

?>