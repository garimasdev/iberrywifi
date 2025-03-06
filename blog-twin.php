<?php
$servername = "localhost";  // Usually localhost for Plesk
$username = "Blogs"; // Database username
$password = "Bhupa@898"; // Database password
$dbname = "admin_blog";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if a specific post ID is requested
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Single post view
        $post_id = $_GET['id'];
        
        // Fetch the specific post
        $postStmt = $conn->prepare("SELECT * FROM posts WHERE id = :post_id");
        $postStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $postStmt->execute();
        $post = $postStmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$post) {
            // Post not found, redirect to main blog page
            header("Location: blog-twin.php");
            exit;
        }
    } else {
        // Main blog page - fetch latest post
        $postStmt = $conn->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT 1");
        $postStmt->execute();
        $post = $postStmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$post) {
            // No posts exist
            $post = null;
        }
    }
    
    // Fetch subheadings for the post (if we have a post)
    if($post) {
        $subheadingStmt = $conn->prepare("SELECT * FROM subheadings WHERE post_id = :post_id");
        $subheadingStmt->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
        $subheadingStmt->execute();
        $subheadings = $subheadingStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $subheadings = [];
    }

    // Fetch recent posts for sidebar (excluding current post if viewing a specific one)
    if(isset($post_id)) {
        $stmt = $conn->prepare("SELECT * FROM posts WHERE id != :current_id ORDER BY created_at DESC LIMIT 5");
        $stmt->bindParam(':current_id', $post_id, PDO::PARAM_INT);
    } else {
        $stmt = $conn->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
    }
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Blog</title>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/prettyPhoto.css">
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" href="rs-plugin/css/settings.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/colors/turquoise.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600,700">


    <!-- Javascripts --> 
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap-hover-dropdown.min.js"></script> 
    <script type="text/javascript" src="js/owl.carousel.min.js"></script> 
    <script type="text/javascript" src="js/jquery.parallax-1.1.3.js"></script>
    <script type="text/javascript" src="js/jquery.nicescroll.js"></script>  
    <script type="text/javascript" src="js/jquery.prettyPhoto.js"></script> 
    <script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script> 
    <script type="text/javascript" src="js/jquery.forms.js"></script> 
    <script type="text/javascript" src="js/jquery.sticky.js"></script> 
    <script type="text/javascript" src="js/waypoints.min.js"></script> 
    <script type="text/javascript" src="js/jquery.isotope.min.js"></script> 
    <script type="text/javascript" src="js/jquery.gmap.min.js"></script> 
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="rs-plugin/js/jquery.themepunch.tools.min.js"></script> 
    <script type="text/javascript" src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script> 
    <script type="text/javascript" src="js/custom.js"></script> 


    <style>

        .navbar-nav {
            margin-right: -70px !important;
        }
        .top-header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 12px 15px;
        }
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f7f7;
        }

        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-weight: bolder !important;
            color: #000000;
        }

        h4 {
            font-size: 16px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        a {
            text-decoration: none;
            color: #007bff;
            transition: all 0.3s ease;
        }
        
        a:hover {
            color: #0056b3;
        }
        
        img {
            max-width: 100%;
            height: auto;
        }
        
        /* Blog Layout */
        .blog-single {
            padding: 50px 0;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
        }
        
        .col-lg-8, .col-lg-4 {
            padding: 15px;
        }
        
        .col-lg-8 {
            width: 66.67%;
        }
        
        .col-lg-4 {
            width: 33.33%;
        }
        
        .m-15px-tb {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        
        /* Article Styles */
        .article {
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.07);
            margin-bottom: 30px;
        }
        
        .article-img img {
            width: 100%;
            border-radius: 5px 5px 0 0;
        }
        
        .article-title {
            padding: 30px 30px 20px;
        }
        
        .article-title h6 a {
            color: #777;
            font-weight: 600;
            font-size: 14px;
        }
        
        .article-title h2 {
            margin: 15px 0;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.4;
        }
        
        .media {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }
        
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }
        
        .media-body label {
            display: block;
            font-weight: 600;
            color: #333;
        }
        
        .media-body span {
            color: #777;
            font-size: 14px;
        }
        
        .article-content {
            padding: 0 30px 30px;
        }
        
        .article-content p {
            margin-bottom: 20px;
        }
        
        .article-content h4 {
            margin: 30px 0 20px;
            font-weight: 600;
        }
        
        blockquote {
            padding: 20px 30px;
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
            margin: 20px 0;
        }
        
        blockquote p {
            font-style: italic;
            margin-bottom: 10px !important;
        }
        
        .blockquote-footer {
            font-size: 14px;
            color: #6c757d;
        }
        
        /* Tag Cloud */
        .nav.tag-cloud {
            padding: 15px 30px 30px;
            display: flex;
            flex-wrap: wrap;
        }
        
        .nav.tag-cloud a {
            background-color: #f8f9fa;
            padding: 7px 15px;
            border-radius: 30px;
            margin-right: 10px;
            margin-bottom: 10px;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav.tag-cloud a:hover {
            background-color: #007bff;
            color: #fff;
        }
        
        /* Comment Form */
        .contact-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.07);
        }

        .contact-form h4 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .name-field, .email-field {
            flex: 1;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: all 0.3s;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .px-btn {
            padding: 12px 30px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s;
        }

        .px-btn:hover {
            background-color: #0056b3;
        }

        .px-btn span {
            margin-right: 10px;
        }

        .px-btn .arrow {
            width: 8px;
            height: 8px;
            border-right: 2px solid #fff;
            border-top: 2px solid #fff;
            transform: rotate(45deg);
        }

        
        /* Sidebar Widgets */
        .blog-aside {
            position: sticky;
            top: 30px;
        }
        
        .widget {
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.07);
            margin-bottom: 30px;
        }
        
        .widget-title {
            padding: 20px 30px;
            border-bottom: 1px solid #eee;
        }
        
        .widget-title h3 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }
        
        .widget-body {
            padding: 30px;
        }
        
        /* Author Widget */
        .widget-author .media {
            margin-bottom: 20px;
        }
        
        .widget-author h6 {
            font-weight: 600;
            margin: 0;
        }
        
        /* Latest Post Widget */
        .latest-post-aside {
            display: flex;
            margin-bottom: 20px;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        
        .latest-post-aside:last-child {
            margin-bottom: 0;
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .lpa-left {
            padding-right: 15px;
        }
        
        .lpa-title h5 {
            font-size: 15px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .lpa-meta {
            font-size: 12px;
        }
        
        .lpa-meta a {
            color: #777;
            margin-right: 10px;
        }
        
        .lpa-right {
            min-width: 100px;
        }
        
        .lpa-right img {
            border-radius: 3px;
        }
        
        /* Responsive Design */
        @media (max-width: 991px) {
            .col-lg-8, .col-lg-4 {
                width: 100%;
            }
            
            .blog-aside {
                position: static;
            }
        }
        
        @media (max-width: 767px) {
            .article-title h2 {
                font-size: 22px;
            }
            
            .row {
                margin: 0;
            }
            
            .col-md-6 {
                width: 100%;
                padding: 0 0 15px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Top header -->
    <div id="top-header">
        <div class="top-header-container">
        <div class="row">
            <div class="col-xs-6">
            <div class="th-text pull-left">
                <div class="th-item"> <a href="call:+91 9855021117"><i class="fa fa-phone"></i>+91 98550 21117</a> </div>
                <div class="th-item"> <a href="mailto:info@iberrywifi.com"><i class="fa fa-envelope"></i>info@iberrywifi.com</a></div>
            </div>
            </div>
            <div class="col-xs-6">
            <div class="th-text pull-right">
                <div class="th-item">
                <div class="social-icons"> 
                    <a href="https://www.facebook.com/people/Iberry-Wi-fi-Solutions/61551759369319/?sk=friends_likes"><i class="fa fa-facebook"></i></a> 
                    <a href="http://www.twitter.com/"><i class="fa fa-twitter"></i></a> 
                    <a href="https://www.instagram.com/iberrywifisolutions/"><i class="fa fa-instagram"></i></a> 
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    
    <!-- Header -->
    <header>
        <!-- Navigation -->
        <div class="navbar yamm navbar-default" id="sticky">
        <div class="container">
            <div class="navbar-header">
            <!-- login button -->
            <a href="https://ibudha.com/dev/mikifi/login" data-target="#navbar-collapse-grid" class="navbar-toggle">
                <i class="fa fa-sign-in" style="font-size: 20px;"></i> <!-- Login Icon -->
            </a>
            <button type="button" data-toggle="collapse" data-target="#navbar-collapse-grid" class="navbar-toggle"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a href="index.html" class="navbar-brand">         
            <!-- Logo -->
            <div id="logo"> 
                <img id="default-logo" src="images/iberry-logo.png" alt="Heritagehotel" style="height:55px;"> 
                <img id="retina-logo" src="images/iberry-logo.png" alt="Heritagehotel" style="height:84px; width: 120px; margin-top: -15px;"> </div>
            </a> </div>
            <div id="navbar-collapse-grid" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li> <a href="index.html">Home</a>
                </li>
                <li class="active"><a href="aboutus.html">About Us</a></li>
                <li class="dropdown"> <a href="#" data-toggle="dropdown" class="dropdown-toggle js-activated">Business Services<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    
                    <li><a href="firewall_security_wifi_hotspot.html">Firewall & Hotspot Services</a></li>
                    <li><a href="vpn_solutions.html">VPN Services</a></li>
                    <li><a href="intercom_ipbx.html">IPBX & Intercom Services</a></li>
                    <li><a href="wifi_installation.html">WiFi Installation Solutions</a></li>
                    <li><a href="camera_installation.html">Camera Installation Services</a></li>
                    <li><a href="automation.html">Office & Home Automation Services</a></li>
                    <li><a href="digital_signage.html">Digital Signage Solutions</a></li>
                    <!-- <li><a href="software-list.html">VOIP & IPPBX Services</a></li>
                    <li><a href="firewall-list.html">WiFi Solutions</a></li>
                    <li><a href="software-list.html">Cabling Networking</a></li> -->
                </ul>
                </li>
                <li class="dropdown"> <a href="#" data-toggle="dropdown" class="dropdown-toggle js-activated">Software Services<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    
                    <li><a href="qr_code_intercom.html">QR Code Intercom System</a></li>
                    <li><a href="food_order.html">Food Ordering System</a></li>
                </ul>
                </li>
                <li class="dropdown"> <a href="#" data-toggle="dropdown" class="dropdown-toggle js-activated">Pricing<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="pricing.html">Firewall & Hotspot Pricing</a></li>
                    <li><a href="hospitality_software.html">Hospitality Software & Services Pricing</a></li>
                </ul>
                </li>
                <li> <a href="contact-01.html">Contact</a>
                <li> <a href="blog.html">Blog</a>
                <li> <a href="https://ibudha.com/dev/mikifi/login">Login</a> </li>
                </li>
                <li style="height:80px;"><a href="https://www.google.com/maps/place/Iberry+Wifi+Corporation/@30.7371149,76.6781289,17z/data=!3m1!4b1!4m6!3m5!1s0x390fef200cac8599:0x470654270ca2bc93!8m2!3d30.7371149!4d76.6807038!16s%2Fg%2F11ghnbw2q4?hl=en-US&entry=ttu&g_ep=EgoyMDI1MDEwNy4wIKXMDSoASAFQAw%3D%3D"> <img class="location" src="images/2.png"></a></li>
            </ul>
            </div>
        </div>
        </div>
    </header>

    <div class="blog-single">
        <div class="container">
            <div class="row align-items-start">
                <!-- Main Content Column -->
                <div class="col-lg-8 m-15px-tb">
                    <!-- Article Section -->
                    <article class="article">
                        <div class="article-img">
                            <img src="<?= htmlspecialchars('http://iberrywifi.com/' . $post['image']) ?>" alt="Blog Featured Image">
                        </div>
                        <div class="article-title">
                            <h6><a href="#">Lifestyle</a></h6>
                            <h2><?= htmlspecialchars($post['title']) ?></h2>
                            <div class="media">
                                <div class="media-body">
                                    <label>Jaswinder Singh</label>
                                    <span><?= date("d M Y", strtotime($post['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="article-content">
                            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                             <!-- Loop through subheadings -->
                            <?php foreach ($subheadings as $subheading): ?>
                                <h4><?= htmlspecialchars($subheading['subheading']) ?></h4>
                                <p><?= nl2br(htmlspecialchars($subheading['content'])) ?></p>
                            <?php endforeach; ?>
                        </div>
                        <div class="nav tag-cloud">
                            <a href="#">Design</a>
                            <a href="#">Development</a>
                            <a href="#">Travel</a>
                            <a href="#">Web Design</a>
                            <a href="#">Marketing</a>
                            <a href="#">Research</a>
                            <a href="#">Managment</a>
                        </div>
                    </article>
                    

                    <!-- Comment Form Section -->
                    <div class="contact-form article-comment">
                        <h4>Leave a Reply</h4>
                        <form id="contact-form" method="POST">
                            <div class="form-row">
                                <div class="form-group name-field">
                                    <input name="Name" id="name" placeholder="Name *" class="form-control" type="text">
                                </div>
                                <div class="form-group email-field">
                                    <input name="Email" id="email" placeholder="Email *" class="form-control" type="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name="message" id="message" placeholder="Your message *" rows="4" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="send">
                                    <button class="px-btn theme"><span>Submit</span> <i class="arrow"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Sidebar Column -->
                <div class="col-lg-4 m-15px-tb blog-aside">
                    <!-- Author Widget -->
                    <div class="widget widget-author">
                        <div class="widget-title">
                            <h3>About Iberry</h3>
                        </div>
                        <div class="widget-body">
                            <div class="media align-items-center">
                                <div class="avatar">
                                    <img src="images/iberry-logo.png" alt="Author Avatar">
                                </div>
                                <div class="media-body">
                                    <h4>Iberry WiFi Security Solutions</h4>
                                </div>
                            </div>
                            <p>We deliver high-quality services to our clients, specializing in IT solutions. Our offerings provide a range of benefits to organizations.</p>
                        </div>
                    </div>
                    
                    
                    <!-- Latest Post Widget -->
                    <div class="widget widget-latest-post">
                        <div class="widget-title">
                            <h3>Latest Posts</h3>
                        </div>
                        <div class="widget-body">
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $recentPost): ?>
                                <div class="latest-post-aside media">
                                    <div class="lpa-left media-body">
                                        <div class="lpa-title">
                                            <h5><a href="blog-twin.php?id=<?php echo $recentPost['id']; ?>"><?php echo htmlspecialchars($recentPost['title']); ?></a></h5>
                                        </div>
                                        <div class="lpa-meta">
                                            <a class="name" href="#">Jaswinder Singh</a>
                                            <a class="date" href="#"><?php echo date("d M Y", strtotime($recentPost['created_at'])); ?></a>
                                        </div>
                                    </div>
                                    <div class="lpa-right">
                                        <a href="blog-twin.php?id=<?php echo $recentPost['id']; ?>">
                                        <img src="<?php echo htmlspecialchars('http://iberrywifi.com/' . $recentPost['image']); ?>" alt="Blog Featured Image">
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No posts found.</p>
                        <?php endif; ?>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>