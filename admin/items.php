<?php
session_start();
$PageTitle = "Items";
include "init.php";
?>
<div class="container items">
<?php
if(isset($_SESSION['admin'])){
    $action = isset($_GET['action']) ?  $_GET['action'] : 'manage' ;
    if($action == 'manage'){                                                // Manage Page
        echo '<h1 class="text-center"> Manage Items</h1>';       
        $stmt = $con->prepare("SELECT 
                                    items.* , categories.Name AS Category_Name , users.Username
                                FROM 
                                    items 
                                INNER JOIN 
                                    categories 
                                ON 
                                    categories.ID = items.Cat_ID 
                                INNER JOIN 
                                    users 
                                ON 
                                    users.UserID = items.Member_ID ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Username</th>
                        <th scope="col">Category</th>
                        <th scope="col">Add Date</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($rows as $item){?>
                    <tr>
                        <th scope="row"><?php echo $item["ID"]?></th>
                        <td><?php echo $item["Name"]?></td>
                        <td><?php echo $item["Description"]?></td>
                        <td><?php echo $item["Price"]?></td>
                        <td><?php echo $item["Username"]?></td>
                        <td><?php echo $item["Category_Name"]?></td>
                        <td><?php echo $item["Add_Date"]?></td>
                        <td>
                            <a href="?action=edit&ID=<?php echo $item['ID']?>" class="btn btn-success mr-1"><i class="fa fa-edit"></i> Edit </a>
                            <a href="?action=delete&ID=<?php echo $item['ID']?>" class="btn btn-danger confirm mr-1"><i class="fa fa-times"></i> Delete </a> 
                            <?php if($item["Approve"] == '0' ){ ?>
                            <a href="?action=approve&ID=<?php echo $item['ID']?>" class="btn btn-primary confirm"><i class="fa fa-times"></i> Approve </a> 
                            <?php } ?>                       
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <a href="?action=add&userID=<?php echo $row['UserID']?>" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> Add New item </a>
        <?php
    }elseif($action == 'add'){                                             //Add page 
    ?>
        <div class="item">
            <h1 class="text-center">Add Page</h1>
            <form class="" action="?action=insert" method="POST">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" placeholder="Name of the item" required="required"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control" placeholder="Describe the item"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                        <input type="text" name="price" class="form-control" placeholder="Price of the item"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Country</label>
                    <div class="col-sm-10">
                        <input type="text" name="country" class="form-control" placeholder="Country of made"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="status">Status</label>
                    <div class="col-sm-10">
                        <select id="status" name="status">
                            <option>...</option>
                            <option value="new">New</option>
                            <option value="like new">Like New</option>
                            <option value="used">Used</option>
                            <option value="very old">Very Old</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="member">Member</label>
                    <div class="col-sm-10">
                        <select id="member" name="member">
                            <option>...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users WHERE GroupID = 1");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){ 
                            ?>
                            <option value="<?php echo $user['UserID'] ?>"><?php echo $user['Username'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="category">Category</label>
                    <div class="col-sm-10">
                        <select id="category" name="category">
                            <option>...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $categories = $stmt->fetchAll();
                                foreach($categories as $category){ 
                            ?>
                            <option value="<?php echo $category['ID'] ?>"><?php echo $category['Name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                <button type="submit" class="btn btn-primary col-sm-2 m-auto">Add Item</button>
                </div>
            </form>
        </div>
    <?php
    }elseif($action == 'approve'){                                        // Pending Items
        echo '<h1 class="text-center"> Approved item</h1>';
        $id=isset($_GET["ID"]) && is_numeric ($_GET['ID']) ? intval($_GET['ID']) : 0 ;
        $stmt = $con->prepare("SELECT * FROM items WHERE Approve = 0 ");
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE ID = :id ");
            $stmt->bindparam(':id', $id);
            $stmt->execute();
            echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Item Approved </div>' ;
            redirect("" , "previous");
        }else{
            redirect("There is no such ID" );
        }

    }elseif($action == 'edit'){                                       // Edit page
       // Check if Get request userID is numeric and get the integer value of it 
        $id = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0 ; 
        $stmt=$con->prepare("SELECT * FROM items WHERE ID = $id "); // Select all data on this ID
        $stmt->execute();                                             // Execute  Query
        $item = $stmt->fetch();                                       // Fetch The Data
        if($stmt->rowCount() > 0){                                  // check if there is such ID
        ?>
         <div class="item">
            <h1 class="text-center">Edit Page</h1>
            <form class="" action="?action=update" method="POST">
            <input type="hidden" name="id" value="<?php echo $id ?>">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" placeholder="Name of the item" value="<?php echo $item['Name'] ?>"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="form-control" placeholder="Describe the item" value="<?php echo $item['Description'] ?>"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                        <input type="text" name="price" class="form-control" placeholder="Price of the item" value="<?php echo $item['Price'] ?>"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Country</label>
                    <div class="col-sm-10">
                        <input type="text" name="country" class="form-control" placeholder="Country of made" value="<?php echo $item['Country_Made'] ?>"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="status">Status</label>
                    <div class="col-sm-10">
                        <select id="status" name="status">
                            <option>...</option>
                            <option value="new" <?php if($item['Status'] == 'new'){echo 'selected';} ?>>New</option>
                            <option value="like new" <?php if($item['Status'] == 'like new'){echo 'selected';} ?>>Like New</option>
                            <option value="used" <?php if($item['Status'] == 'used'){echo 'selected';} ?>>Used</option>
                            <option value="very old" <?php if($item['Status'] == 'very old'){echo 'selected';} ?>>Very Old</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="member">Member</label>
                    <div class="col-sm-10">
                        <select id="member" name="member">
                            <option>...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users WHERE GroupID = 1");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){ 
                            ?>
                                <option value="<?php echo $user['UserID'] ?>"  <?php if($item['Member_ID'] == $user['UserID']){echo 'selected';} ?>>
                                <?php echo $user['Username'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="category">Category</label>
                    <div class="col-sm-10">
                        <select id="category" name="category">
                            <option>...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $categories = $stmt->fetchAll();
                                foreach($categories as $category){ 
                            ?>
                            <option value="<?php echo $category['ID'] ?>" <?php if($item['Cat_ID'] == $category['ID']){echo 'selected' ;} ?>><?php echo $category['Name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                <button type="submit" class="btn btn-primary col-sm-2 m-auto">Update Item</button>
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
            $ID=$_POST['id'];
            $name= $_POST['name'];
            $description= $_POST['description'];
            $price=$_POST['price'];
            $country=$_POST['country'];
            $status=$_POST['status'];
            $stmt=$con->prepare("UPDATE items SET Name = :name , Description = :description , Price = :price , Country_Made = :country , Status = :status WHERE ID = :id " );
            $stmt->bindparam(":name" , $name);
            $stmt->bindparam(":description" , $description);
            $stmt->bindparam(":price" , $price);
            $stmt->bindparam(":country" , $country);
            $stmt->bindparam(":status" , $status);
            $stmt->bindparam(":id",$ID);
            $stmt->execute();
            echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>' ;
            redirect("","items","items.php" );
        }
        else{
            redirect("Sorry u cant enter this page directly","home" );
        }       
    }elseif($action == 'insert'){                                    // Insert page
        echo '<h1 class="text-center"> Insert Item</h1>';                                 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name= $_POST['name'];
            $description= $_POST['description'];
            $price=$_POST['price'];
            $country=$_POST['country'];
            $status=$_POST['status'];
            $member=$_POST['member'];
            $category=$_POST['category'];
                $stmt=$con->prepare("INSERT INTO items (Name,Description,Price,Country_Made,Status,Member_ID,Cat_ID) VALUES (:name,:description,:price,:country,:status,:member,:cat_id)" );
                $stmt->bindparam(":name" , $name);
                $stmt->bindparam(":description" , $description);
                $stmt->bindparam(":price" , $price);
                $stmt->bindparam(":country" , $country);
                $stmt->bindparam(":status" , $status);
                $stmt->bindparam(":member" , $member);
                $stmt->bindparam(":cat_id" , $category);
                $stmt->execute();
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Added </div>' ;
                redirect("","items","items.php");
        }else{
            redirect("u cant access this page directly","home","dashboard.php");
        }
    }elseif($action == 'delete'){                                // Delete Page 
        echo '<h1 class="text-center"> Delete Page</h1>';
            $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0 ;
            $check = checkitem("ID" , "items" , $ID);
            if($check > 0){
                $stmt=$con->prepare("DELETE FROM items WHERE ID = :id ");
                $stmt->bindparam(":id" , $ID);
                $stmt->execute();
            //  reset_id("items","ID");
                echo '<div class="alert alert-danger ">' . $stmt->rowCount() . ' Record Deleted </div>' ;
                redirect("" , "items" , "items.php");
            }else{
                redirect("There is no such ID" , "items" , "items.php");
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
