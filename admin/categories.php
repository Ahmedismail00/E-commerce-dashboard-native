<?php
session_start();
$PageTitle = "Caregories";
include "init.php";
?>
<div class="container categories">
<?php
if(isset($_SESSION['admin'])){
    $action = isset($_GET['action']) ?  $_GET['action'] : 'manage' ;
    if($action == 'manage'){                                                // Manage Page
        echo '<h1 class="text-center"> Manage Categories</h1>';       
        $sort = "ASC";                                    
        $sort_array = array('ASC' , 'DESC');
        $sort = isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array) ? $_GET['sort'] : $sort ;
        $stmt = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $sort = "ASCC" ;
        ?>
        <div class="panel-heading">
            <span class="font-weight-bold">Manage Categories</span>
            <span class="float-right">Ordering : 
                <a href="?sort=ASC" class=" <?php if($_GET['ASC']){ echo "active" ; } ?>" >ASC</a> | 
                <a href="?sort=DESC" class=" sorting <?php if($_GET['DESC']){ echo "active" ; } ?>">DESC</a> 
            </span>
        </div>
        <div class="panel">
            <?php foreach($rows as $item){ ?>
            <div class="row panel-body">
                <div class="col-6">
                    <h3 class="cat-name view"><?php echo $item["Name"]?></h3>
                    <div class="full-view">
                        <p><?php echo $item["Description"]?></p>
                        <?php if($item["Visibility"] == 0 ||$item["Allow_Comment"] == 0 || $item["Allow_Adv"] == 0 ){ ?>
                        <div class="pt-2 pb-2">
                            <?php if($item["Visibility"] == 0){?>
                            <span class="visibility mr-1">Hidden</span> 
                            <?php } ?>
                            <?php if($item["Allow_Comment"] == 0){?>
                            <span class="comment mr-1">Comment Disabled</span> 
                            <?php } ?>
                            <?php if($item["Allow_Adv"] == 0){?>
                            <span class="adv ">Adv Disabled</span> 
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-6 cat">
                    <span class="float-right hidden-button" >
                        <a href="?action=edit&ID=<?php echo $item['ID']?>" class="btn btn-success btn-sm mr-1"><i class="fa fa-edit"></i> Edit </a>
                        <a href="?action=delete&ID=<?php echo $item['ID']?>" class="btn btn-danger btn-sm confirm mr-1"><i class="fa fa-times"></i> Delete </a>
                    </span>
                </div>
            </div>
            <?php } ?>
        </div>   
        <a href="?action=add&userID=<?php echo $row['UserID']?>" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add New Categories </a>
        <?php
    }elseif($action == 'add'){                                             //Add page 
    ?>
        <div class="container">
            <h1 class="text-center">Add Page</h1>
            <form class="" action="?action=insert" method="POST">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" placeholder="Name of the categorie" required="required"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control" placeholder="Describe the categorie"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Ordering</label>
                    <div class="col-sm-10">
                        <input type="text" name="order" class="form-control" placeholder="Number to arrange the categorie"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label ">Visible</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div class="form-check">
                            <input type="radio" name="visible" class="form-check-input" value="1" id="vis-yes" checked>
                            <label class="form-check-label" for="vis-yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="visible" class="form-check-input" value="0" id="vis-no">
                            <label for="vis-no" class="form-check-label">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label ">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div class="form-check">
                            <input type="radio" name="comment" class="form-check-input" value="1" id="com-yes" checked>
                            <label class="form-check-label" for="com-yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="comment" class="form-check-input" value="0" id="com-no">
                            <label for="com-no" class="form-check-label">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label ">Allow Adv</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div class="form-check">
                            <input type="radio" name="adv" class="form-check-input" value="1" id="adv-yes" checked>
                            <label class="form-check-label" for="adv-yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="adv" class="form-check-input" value="0" id="adv-no">
                            <label for="adv-no" class="form-check-label">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                <button type="submit" class="btn btn-primary col-sm-2 m-auto">Submit</button>
                </div>
            </form>
        </div>
    <?php
    }elseif($action == 'edit'){                                       // Edit page
       // Check if Get request userID is numeric and get the integer value of it 
        $id = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0 ; 
        $stmt=$con->prepare("SELECT * FROM categories WHERE ID = $id "); // Select all data on this ID
        $stmt->execute();                                             // Execute  Query
        $row = $stmt->fetch();                                       // Fetch The Data
        if($stmt->rowCount() > 0){                                  // check if there is such ID
        ?>
        <div class="container">
            <h1 class="text-center">Edit Page</h1>
            <form class="" action="?action=update" method="POST">
            <input type="hidden" value="<?php echo $id ;?>" name="ID"/>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="<?php echo $row['Name']; ?>" placeholder="Name of the categorie" required="required"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control" value="<?php echo $row['Description']; ?>" placeholder="Describe the categorie"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Ordering</label>
                    <div class="col-sm-10">
                        <input type="text" name="order" class="form-control" value="<?php echo $row['Ordering']; ?>" placeholder="Number to arrange the categorie"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label ">Visible</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div class="form-check">
                            <input type="radio" name="visible" class="form-check-input" value="1" id="vis-yes" <?php if(radio('Visibility' , 'categories' , $id) == '0'){echo 'checked' ;}  ?>>
                            <label class="form-check-label" for="vis-yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="visible" class="form-check-input" value="0" id="vis-no" <?php if(radio('Visibility' , 'categories' , $id) == '1'){echo 'checked' ;}  ?> >
                            <label for="vis-no" class="form-check-label">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label ">Allow Commenting</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div class="form-check">
                            <input type="radio" name="comment" class="form-check-input" value="1" id="com-yes" <?php if(radio('Allow_Comment' , 'categories' , $id) == '1'){echo 'checked' ;}  ?>>
                            <label class="form-check-label" for="com-yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="comment" class="form-check-input" value="0" id="com-no" <?php if(radio('Allow_Comment' , 'categories' , $id) == '0'){echo 'checked' ;}  ?>>
                            <label for="com-no" class="form-check-label">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label ">Allow Adv</label>
                    <div class="col-sm-10 col-md-6 ">
                        <div class="form-check">
                            <input type="radio" name="adv" class="form-check-input" value="1" id="adv-yes" <?php if(radio('Allow_Adv' , 'categories' , $id) == '1'){echo 'checked' ;}  ?>>
                            <label class="form-check-label" for="adv-yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="adv" class="form-check-input" value="0" id="adv-no" <?php if(radio('Allow_Adv' , 'categories' , $id) == '0'){echo 'checked' ;}  ?>>
                            <label for="adv-no" class="form-check-label">No</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                <button type="submit" class="btn btn-primary col-sm-2 m-auto">Submit</button>
                </div>
            </form>
        </div>
        <?php

       }else{
        redirect("there is no such ID");
       }
    }elseif($action == 'update'){                                    // Update page
        echo '<h1 class="text-center"> Update Page</h1>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ID= $_POST['ID'];
            $name= $_POST['name'];
            $description= $_POST['description'];
            $order=$_POST['order'];
            $visiblality=$_POST['visible'];
            $comment=$_POST['comment'];
            $adv=$_POST['adv'];
            $checkitem = checkitem("Name","categories",$name);  
            $formerror = array();                                               // Validate the form
            if(empty($name)){ $formerror[] = 'Username cant be empty';}
            if(empty($order)){ $formerror[] = 'Order cant be empty';}
            if(empty($formerror)){                                              //check if there is no error 
                $stmt = $con->prepare("UPDATE categories SET Name = :name , Description = :description , Ordering = :order , Visibility = :visibility , Allow_Comment = :comment , Allow_Adv = :adv WHERE ID = :id ");
                $stmt->bindparam(":name",$name);
                $stmt->bindparam(":description",$description);
                $stmt->bindparam(":order",$order);
                $stmt->bindparam(":visibility",$visiblality);
                $stmt->bindparam(":comment",$comment);
                $stmt->bindparam(":adv",$adv);
                $stmt->bindparam(":id",$ID);
                $stmt->execute();
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>' ;
                redirect("","categories","categories.php" );
            }else{
                foreach($formerror as $error){
                    echo '<div class = "alert alert-danger">' . $error . '</div>';
                }
                redirect("","previous" );
            }
        }
        else{
            redirect("Sorry u cant enter this page directly","home" );
        }        
    }elseif($action == 'insert'){                                    // Insert page
        echo '<h1 class="text-center"> Insert Page</h1>';                                 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name= $_POST['name'];
            $description= $_POST['description'];
            $order=$_POST['order'];
            $visiblality=$_POST['visible'];
            $comment=$_POST['comment'];
            $adv=$_POST['adv'];
            $checkitem = checkitem("Name","categories",$name);           //Check if username is already existed 
            $formerror = array();                                           // Validate the form
            if($checkitem == 1){ $formerror[] = 'Name is already exist';}
            if(empty($formerror)){                                          //check if there is no error 
                $stmt=$con->prepare("INSERT INTO categories (Name,Description,Ordering,Visibility,Allow_Comment,Allow_Adv) 
                VALUES (:name,:description,:order,:visible,:comment,:adv)" );
                $stmt->bindparam(":name" , $name);
                $stmt->bindparam(":description" , $description);
                $stmt->bindparam(":order" , $order);
                $stmt->bindparam(":visible" , $visiblality);
                $stmt->bindparam(":comment" , $comment);
                $stmt->bindparam(":adv" , $adv);
                $stmt->execute();
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Added </div>' ;
                redirect("","categories","categories.php");
            }else{
                foreach($formerror as $error){
                    echo '<div class = "alert alert-danger">' . $error . '</div>';
                }
                redirect("","previous");
            }
        }else{
            redirect("u cant access this page directly","home" );
        }
    }elseif($action == 'delete'){                                // Delete Page 
        echo '<h1 class="text-center"> Delete Page</h1>';
            $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0 ;
            $check = checkitem("ID" , "categories" , $ID);
            if($check > 0){
                $stmt=$con->prepare("DELETE FROM categories WHERE ID = :id ");
                $stmt->bindparam(":id" , $ID);
                $stmt->execute();
                echo '<div class="alert alert-danger ">' . $stmt->rowCount() . ' Record Deleted </div>' ;
                redirect("" , "categories" , "categories.php");
            }else{
                redirect("There is no such ID" , "categories" , "categories.php");
            }
        }
    
    include  $tpl . 'footer.php';
}
    else {
        header("location: index.php");
        exit();
    }
        ?>  
    </div>
