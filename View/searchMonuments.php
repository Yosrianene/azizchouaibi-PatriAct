<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>PatriAct - Explore Tunisian Civilizations</title>
  <meta name="description" content="">
  <meta name="keywords" content="Tunisian Civilizations, Monuments, Culture">

  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Cardo:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
  <style>
    body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f9f9f9;
}

h2 {
    text-align: center;
    color: #333;
}

.card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 300px;
    overflow: hidden;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-content {
    padding: 15px;
}

.card-content h3 {
    font-size: 1.5rem;
    margin: 0 0 10px;
    color: #333;
}

.card-content p {
    margin: 5px 0;
    color: #555;
}

p strong {
    color: #000;
}
.civilisation-description {
    margin: 20px 0;
    padding: 15px;
    background: #f4f4f4;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.civilisation-description h3 {
    margin-bottom: 10px;
    color: #333;
}

.civilisation-description p {
    color: #555;
    font-size: 1rem;
    line-height: 1.6;
}


  </style>

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="assets/img/logo.png" alt=""> 
        <i class="bi"></i>
        <h1 class="sitename">PatriAct</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.html" class="active">Home</a></li>
          <li><a href="about.html">About</a></li>
          <li class="dropdown"><a href=""><span>Services</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="createAPost.html">Create a post</a></li>
              <li><a href="viewPosts.html">View Posts</a></li>
              <li><a href="gallery.html">Search monuments</a></li>
              <li><a href="gallery.html">Souvenirs</a></li>
              <li><a href="gallery.html">Expeditions and Events</a></li>
              <li><a href="gallery.html">Travel</a></li>
            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="header-social-links">
        <a href="#" class=""><i class="loginBTN"></i> Login</a>
        <a href="#" class=""><i class="signupBTN"></i> Signup</a>
        <a href="chatbot.php"  target="_blank"> <i > Chatbot</i></a>
      </div>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 text-center" data-aos="fade-up" data-aos-delay="100">
            <h2><span>Search by </span><span class="underlight">Culture</span></h2>
            <p>You can search for monuments by the civilization that left them. Type the name of a civilization to explore.</p>

            <div class="search-container">
            <form action="" method="POST">
    <label for="civilisation-select">Choose a Civilization:</label>
    <select name="search" id="civilisation-select">
        <option value="" disabled selected>Select a civilization</option>
        <option value="French Colonial Era">French Colonial Era</option>
        <option value="Ottoman Empire">Ottoman Empire</option>
        <option value="Carthaginian Civilization">Carthaginian Civilization</option>
        <option value="Roman Empire">Roman Empire</option>
        <option value="Arab Civilization">Arab Civilization</option>
        <option value="Berber Civilization">Berber Civilization</option>
    </select>
    <button type="submit" id="search-button">Submit</button>
</form>

            </div>
          </div>
        </div>
      </div>
    </section><!-- /Hero Section -->

    <!-- Results Section -->
    <section id="results" class="results section" style="display:none;">
      <div class="container">
        <h3 id="cultureName"></h3>
        <p id="cultureDescription"></p>
        <div id="cultureDetails">
          <p><strong>Monuments:</strong></p>
          <ul id="monumentsList"></ul>
          <p><strong>Clothing:</strong></p>
          <ul id="clothingList"></ul>
          <p><strong>Food:</strong></p>
          <ul id="foodList"></ul>
        </div>
      </div>
    </section><!-- /Results Section -->

  </main>

 
  
  <?php
  if($_SERVER['REQUEST_METHOD'] ==='POST') {
    include_once '../Controller/CivilisationItemsController.php';

    // Get the user input from the form
    $userInput = $_POST['search'] ;
    echo "<h2>Search Results for: " . htmlspecialchars($userInput) . "</h2>";
    
    
    // Initialize the model and fetch information
    $civController = new CivilisationItemsController();
    $result = $civController->DisplayItemsByCivilisation($userInput);

    
    
    if ($result) {
        // Display the civilization's description
        echo "<div class='civilisation-description'>";
        echo "<h3>About the Civilization:</h3>";
        echo "<p>" . htmlspecialchars($result['description']) . "</p>";
        echo "</div>";
    
        // Display items related to the civilization
        echo "<div class='card-container'>";
        foreach ($result['items'] as $item) {
            echo "<div class='card'>";
            echo "<img src='" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['name']) . "' />";
            echo "<div class='card-content'>";
            echo "<h3>" . htmlspecialchars($item['name']) . "</h3>";
            echo "<p><strong>Type:</strong> " . htmlspecialchars($item['type']) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($item['description']) . "</p>";
            if (!empty($item['location'])) {
                echo "<p><strong>Location:</strong> " . htmlspecialchars($item['location']) . "</p>";
            }
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No results found for: " . htmlspecialchars($userInput) . "</p>";
    }
    

  }

?>
 <footer id="footer" class="footer">
    <div class="container">
      <div class="copyright text-center ">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">PatriAct</strong> <span>All Rights Reserved</span></p>
      </div>
      <div class="social-links d-flex justify-content-center">
        <a href=""><i class="bi bi-twitter-x"></i></a>
        <a href=""><i class="bi bi-facebook"></i></a>
        <a href=""><i class="bi bi-instagram"></i></a>
        <a href=""><i class="bi bi-linkedin"></i></a>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader">
    <div class="line"></div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>


<!--
  <script>
    // Object to store civilizations data
    const civilizations = {
      "French": {
        name: "French Colonial Era",
        description: "The French colonial era greatly impacted Tunisia, especially in the urban landscape, culture, and education system. French-style architecture and cultural influences remain visible today.",
        monuments: [
          { name: "Carthage Museum", description: "The museum showcases the history of the Carthaginian civilization, with a French touch in its architecture.", img: "assets/img/searchMonuments/Carthage Museum.jpg", location: "36.8567° N, 10.3220° E" },
          { name: "La Marsa", description: "A French-built seaside resort town in Tunisia, known for its beaches and colonial-era architecture.", img: "assets/img/searchMonuments/La Marsa.jpg", location: "36.8536° N, 10.3032° E" }
        ],
        clothing: [
          { name: "French-style suit", description: "Inspired by European fashion, tailored suits were worn by the elite in Tunisia during the French protectorate.", img: "assets/img/searchMonuments/French-style suit.jpg" },
          { name: "Beret", description: "The beret was worn by many Tunisians influenced by the French in the 20th century.", img: "assets/img/searchMonuments/Clothing.jpg" }
        ],
        food: [
          { name: "Croissant", description: "A legacy of French colonization, this buttery pastry is widely popular in Tunisia.", img: "assets/img/searchMonuments/Croissant.jpg" },
          { name: "Baguette", description: "The baguette is a symbol of French influence on Tunisia's culinary traditions.", img: "assets/img/searchMonuments/Baguette.jpg" }
        ]
      },
      "Ottoman": {
        name: "Ottoman Empire",
        description: "The Ottoman Empire left an indelible mark on Tunisia’s architecture, culture, and governance. The legacy of Ottoman rule is visible in the medina and many buildings across Tunisia.",
        monuments: [
          { name: "Dar Ben Abdallah", description: "A traditional Ottoman house, now a museum showcasing the lifestyle of the time.", img: "C:/xampp/htdocs/PartriAct-main/view/assets/img/searchMonuments/Dar Ben Abdallah.jpg", location: "36.8000° N, 10.1649° E" },
          { name: "Kasbah of Tunis", description: "A historical site that served as the residence of the Ottoman beylical family.", img: "assets/img/searchMonuments/Kasbah of Tunis.jpg", location: "36.7985° N, 10.1774° E" }
        ],
        clothing: [
          { name: "Kaftan", description: "A traditional Ottoman garment worn by men and women, often adorned with intricate designs.", img: "assets/img/searchMonuments/Kaftan.jpg" },
          { name: "Fez", description: "A hat of Ottoman origin, worn by many Tunisians during the period of Ottoman rule.", img: "assets/img/searchMonuments/Fez.jpg" }
        ],
        food: [
          { name: "Makroud", description: "A traditional sweet, often filled with dates and nuts, with Ottoman influences.", img: "assets/img/searchMonuments/Makroud.png" },
          { name: "Couscous", description: "Although native to North Africa, couscous was popularized across the Ottoman Empire.", img: "assets/img/searchMonuments/Couscous.jpg" }
        ]
      },
      "Carthage": {
        name: "Carthaginian Civilization",
        description: "Carthage, one of the greatest ancient civilizations of the Mediterranean, was the center of powerful trade and military control before its fall to Rome.",
        monuments: [
          { name: "Carthage Ruins", description: "The remains of Carthage, with temples and structures that were once the heart of this Phoenician city-state.", img: "assets/img/searchMonuments/Carthage Ruins.jpg", location: "36.8500° N, 10.3242° E" },
          { name: "Tophet of Carthage", description: "An ancient burial site where children were sacrificed to the gods, providing key insights into the Carthaginian culture.", img: "assets/img/searchMonuments/Tophet of Carthage.jpg", location: "36.8595° N, 10.3289° E" }
        ],
        clothing: [
          { name: "Tunica", description: "A simple garment worn by Carthaginian men and women, often made from wool or linen.", img: "assets/img/searchMonuments/Tunica.jpg" },
          { name: "Tunic with Loincloth", description: "A tunic with a wrapped cloth around the waist, a standard garment of Carthaginian soldiers.", img: "assets/img/searchMonuments/Tunic with Loincloth.jpg" }
        ],
        food: [
          { name: "Garum", description: "A fermented fish sauce used widely in ancient Carthage, often added to various dishes.", img: "assets/img/searchMonuments/Garum.jpg"},
          { name: "Olives", description: "Carthage was known for its olive oil production, a staple in their diet and trade.", img: "assets/img/searchMonuments/Olives.jpg" }
        ]
      },
      "Roman": {
        name: "Roman Empire",
        description: "The Roman Empire brought significant cultural, architectural, and political changes to Tunisia after it conquered Carthage in 146 BCE.",
        monuments: [
          { name: "El Djem Amphitheater", description: "One of the best-preserved Roman structures in the world, this amphitheater hosted gladiator battles and other public spectacles.", img: "assets/img/searchMonuments/El Djem Amphitheate.jpg", location: "35.2705° N, 9.3707° E" },
          { name: "Carthage Roman Baths", description: "These ancient baths were part of the grand Roman city of Carthage.", img: "assets/img/searchMonuments/Carthage Roman Baths.jpg", location: "36.8571° N, 10.3229° E" }
        ],
        clothing: [
          { name: "Tunic", description: "A simple, everyday Roman garment worn by both men and women.", img: "assets/img/searchMonuments/Tunic.jpg" },
          { name: "Stola", description: "A long, flowing dress worn by Roman women, symbolizing their status.", img: "assets/img/searchMonuments/Stola.jpg" }
        ],
        food: [
          { name: "Bread and Olive Oil", description: "A staple meal for Romans, often served with fish or vegetables.", img: "assets/img/searchMonuments/Pain et huile d'olive.jpg" },
          { name: "Wine", description: "Wine was a significant part of Roman culture, consumed daily and often used in religious rituals.", img: "assets/img/searchMonuments/Vin.jpg" }
        ]
      },
      "Arab": {
        name: "Arab Civilization",
        description: "The Arab influence in Tunisia began after the Muslim conquest in the 7th century, bringing Islam, Arab culture, and the Arabic language.",
        monuments: [
          { name: "Zitouna Mosque", description: "One of the oldest mosques in the Arab world, symbolizing the arrival of Islam in Tunisia.", img: "assets/img/searchMonuments/Zitouna Mosque.jpg", location: "36.8000° N, 10.1635° E" },
          { name: "Medina of Tunis", description: "A historical city that dates back to the Arab period, full of narrow streets and traditional architecture.", img: "assets/img/searchMonuments/Medina of Tunis.jpg", location: "36.8020° N, 10.1790° E" }
        ],
        clothing: [
          { name: "Jubba", description: "A long robe worn by Arab men, often adorned with embroidery.", img: "assets/img/searchMonuments/Jubba.jpg" },
          { name: "Hijab", description: "A traditional headscarf worn by Muslim women, often as part of the Arab cultural tradition.", img: "assets/img/searchMonuments/Hijab.jpg" }
        ],
        food: [
          { name: "Couscous", description: "A dish of semolina and vegetables, with meat often added, popular across the Arab world.", img: "assets/img/searchMonuments/Couscous arabe.jpg" },
          { name: "Briouat", description: "A sweet or savory pastry that is a favorite in Tunisian Arab cuisine.", img: "assets/img/searchMonuments/Briouat.jpg" }
        ]
      },
      "Berber": {
        name: "Berber Civilization",
        description: "The Berber people, indigenous to North Africa, have had a significant influence on Tunisia's culture, language, and architecture.",
        monuments: [
          { name: "Matmata Troglodyte Houses", description: "Traditional Berber dwellings carved into the earth, offering a glimpse into ancient Berber architecture.", img: "assets/img/searchMonuments/Matmata Troglodyte Houses.jpg", location: "33.4642° N, 9.3842° E" },
          { name: "Ksar Ouled Soltane", description: "A Berber fortified village that is well preserved, offering insight into the Berber way of life.", img: "assets/img/searchMonuments/Ksar Ouled Soltane.jpg", location: "33.5144° N, 9.5833° E" }
        ],
        clothing: [
          { name: "Burnous", description: "A long, hooded cloak worn by Berber men, often made of wool.", img: "assets/img/searchMonuments/Burnous.jpg" },
          { name: "Berber Jewelry", description: "Elaborate silver jewelry is a traditional form of adornment for Berber women.", img: "assets/img/searchMonuments/Berber Jewelry.jpg" }
        ],
        food: [
          { name: "Mechouia", description: "A roasted vegetable salad, often eaten with grilled meats.", img:"assets/img/searchMonuments/Mechouia.jpg" },
          { name: "Tagine", description: "A slow-cooked stew, made with vegetables and meat, reflecting Berber culinary traditions.", img: "assets/img/searchMonuments/Tagine.jpg" }
        ]
      },

      // Add other civilizations here if needed
    };

    // Search functionality
    document.getElementById('search-button').addEventListener('click', function() {
    let searchTerm = document.getElementById('search-input').value.trim().toLowerCase();
    let cultureFound = null;

    // Loop through civilizations to find a match
    for (let key in civilizations) {
        if (key.toLowerCase().includes(searchTerm) || civilizations[key].name.toLowerCase().includes(searchTerm)) {
            cultureFound = civilizations[key];
            break;
        }
    }

    if (cultureFound) {
        // Display culture name and description
        document.getElementById('cultureName').innerText = cultureFound.name;
        document.getElementById('cultureDescription').innerText = cultureFound.description;

        // Display monuments
        let monumentsList = document.getElementById("monumentsList");
        monumentsList.innerHTML = '';
        cultureFound.monuments.forEach(function(monument) {
            let li = document.createElement("li");
            li.innerHTML = `<strong>${monument.name}</strong><p>${monument.description}</p><img src="${monument.img}" alt="${monument.name}" style="width:100px;"> <br><a href="https://www.google.com/maps?q=${monument.location}" target="_blank">View on Map</a>`;
            monumentsList.appendChild(li);
        });

        // Display clothing
        let clothingList = document.getElementById("clothingList");
        clothingList.innerHTML = '';
        cultureFound.clothing.forEach(function(item) {
            let li = document.createElement("li");
            li.innerHTML = `<strong>${item.name}</strong><p>${item.description}</p><img src="${item.img}" alt="${item.name}" style="width:100px;">`;
            clothingList.appendChild(li);
        });

        // Display food
        let foodList = document.getElementById("foodList");
        foodList.innerHTML = '';
        cultureFound.food.forEach(function(foodItem) {
            let li = document.createElement("li");
            li.innerHTML = `<strong>${foodItem.name}</strong><p>${foodItem.description}</p><img src="${foodItem.img}" alt="${foodItem.name}" style="width:100px;">`;
            foodList.appendChild(li);
        });

        // Show results section
        document.getElementById("results").style.display = 'block';
    } else {
        alert('Culture not found. Please try another one.');
    }
});

  </script> 
  --> 

</body>

</html>
