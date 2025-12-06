<?php
session_start();
include 'db_connect.php';

// Check if pet owner is logged in
if (!isset($_SESSION['petowner_logged_in']) || $_SESSION['petowner_logged_in'] !== true) {
    header("Location: pet-login.php");
    exit();
}

// Make sure the username session exists
if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    die("Error: Pet owner information missing. Please log in again.");
}

// -------------------------
// USER SESSION
// -------------------------
$user = $_SESSION['user_name'];

// -------------------------
// BREED LISTS
// -------------------------
$dog_breeds = [
    "Aspin / Askal", "Labrador Retriever", "Golden Retriever", "Shih Tzu",
    "Pomeranian", "Poodle", "Chihuahua", "Pug", "Siberian Husky",
    "German Shepherd", "Beagle", "Dachshund", "French Bulldog",
    "Maltese", "Boxer", "Rottweiler", "Cocker Spaniel",
    "Yorkshire Terrier", "Shiba Inu", "Border Collie",
    "Australian Shepherd", "Jack Russell Terrier", "Doberman Pinscher",
    "Great Dane", "Corgi", "Miniature Pinscher", "Bichon Frise",
    "Bull Terrier", "Cavalier King Charles Spaniel", "Belgian Malinois",
    "Chow Chow", "Alaskan Malamute", "Basenji", "Cane Corso",
    "English Springer Spaniel", "Irish Setter", "Havanese", "Shar Pei",
    "Lhasa Apso", "American Bully", "Pit Bull Terrier", "Bullmastiff",
    "Samoyed", "Tibetan Terrier", "American Eskimo Dog",
    "Old English Sheepdog", "Dalmatian", "Whippet", "Greyhound", "Akita"
];

$cat_breeds = [
    "Puspin", "Domestic Shorthair", "Persian", "Siamese", "Maine Coon",
    "Ragdoll", "Bengal", "British Shorthair", "Scottish Fold",
    "Sphynx", "Norwegian Forest", "Russian Blue", "Exotic Shorthair",
    "Burmese", "Himalayan", "Abyssinian", "Oriental Shorthair",
    "Turkish Angora", "Tonkinese", "Birman", "Savannah", "Cornish Rex",
    "Devon Rex", "Egyptian Mau", "Manx", "Ragamuffin", "Chartreux",
    "Balinese", "Japanese Bobtail", "American Shorthair",
    "Australian Mist", "LaPerm", "Singapura", "Selkirk Rex",
    "Siberian", "Ocicat", "Serengeti", "Pixiebob", "Khao Manee",
    "Korat", "Lykoi", "Peterbald", "Chausie", "Turkish Van",
    "American Bobtail", "Brazilian Shorthair", "California Spangled",
    "Oriental Longhair", "Cymric", "Burmilla"
];



$msg = "";

// -------------------------
// FORM SUBMISSION
// -------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type   = trim($_POST['pet_type']);
    $name   = trim($_POST['pet_name']);
    $gender = trim($_POST['pet_gender']);
    $age    = trim($_POST['pet_age']);
    $breed  = trim($_POST['pet_breed']);

    // Weight validation
    $weight_input = trim($_POST['pet_weight']);
    if (!is_numeric($weight_input)) {
        $msg = "Weight must be a number.";
    } else {
        $weight = (float)$weight_input;
    }

    // Breed validation
    if ($type === 'Dog' && !in_array($breed, $dog_breeds)) {
        $msg = "Invalid dog breed selected.";
    } elseif ($type === 'Cat' && !in_array($breed, $cat_breeds)) {
        $msg = "Invalid cat breed selected.";
    }

    // Symptoms combine (for optional CSV storage)
    $symptoms_csv = "";
    if (!empty($_POST['symptoms'])) {
        $symptoms_csv = implode(", ", array_map('trim', $_POST['symptoms']));
    }

    // Handle avatar upload
    $avatar = null;
    if (!empty($_FILES['avatar']['name'])) {
        $folder = "uploads/";
        if (!is_dir($folder)) mkdir($folder, 0755, true);

        $extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
        $filename = $name . "." . $extension;
        $target = $folder . $filename;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target)) {
            $avatar = $filename;
        }
    }

    // Proceed if no errors
    if ($msg === "") {
        $sql = "INSERT INTO pets
                (pet_type, pet_name, pet_gender, pet_weight, pet_breed, pet_age, pet_symptoms, user_name, avatar)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssdsssss",
            $type,
            $name,
            $gender,
            $weight,
            $breed,
            $age,
            $symptoms_csv,
            $user,
            $avatar
        );

        if ($stmt->execute()) {
            $pet_id = $stmt->insert_id; // Get the new pet_id

            // Insert each symptom into the `symptoms` table
            if (!empty($_POST['symptoms'])) {
                foreach ($_POST['symptoms'] as $symptom) {
                    $symptom = trim($symptom);
                    if ($symptom !== "") {
                        $stmt2 = $conn->prepare("INSERT INTO symptoms (pet_id, symptom, date_added, user_name) VALUES (?, ?, NOW(), ?)");
                        $stmt2->bind_param("iss", $pet_id, $symptom, $user);
                        $stmt2->execute();
                    }
                }
            }

            header("Location: petowner_dashboard.php");
            exit();
        } else {
            $msg = "Error: " . $stmt->error;
        }
    }
}
?>

<!-- HTML form remains unchanged -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Pet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family: Inter, system-ui; background:#eef2ff; margin:0; padding:0;}
        .wrap{max-width:600px;margin:40px auto;background:#fff;padding:30px;border-radius:16px;box-shadow:0 8px 30px rgba(39,45,90,0.1);}
        h2{text-align:center;color:#5c4fff;}
        label{font-weight:600;display:block;margin-top:14px;}
        input, select{width:100%;padding:10px;margin-top:6px;border-radius:10px;border:1px solid #ddd;}
        .btn{margin-top:20px;width:100%;padding:12px;background:#5c4fff;color:#fff;border:none;border-radius:12px;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 8px 20px rgba(92,79,255,0.2);}
        .msg{padding:10px;color:#fff;background:#5c4fff;text-align:center;border-radius:8px;margin-bottom:10px;}
        .back{text-align:center;margin-top:12px;}
        .back a{color:#5c4fff;text-decoration:none;font-weight:600;}
    </style>
</head>
<body>
<div class="wrap">
    <h2>Add Your Pet</h2>
    <?php if (!empty($msg)) echo "<div class='msg'>{$msg}</div>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Type</label>
        <select name="pet_type" id="typeSelect" required>
            <option value="">Select Type</option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
        </select>

        <label>Name</label>
        <input type="text" name="pet_name" required>

        <label>Gender</label>
        <select name="pet_gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label>Age</label>
        <input type="text" name="pet_age" placeholder="e.g. 2 years" required>

        <label>Weight (kg)</label>
        <input type="number" step="0.1" name="pet_weight" placeholder="e.g. 5.3" required>

        <label>Breed</label>
        <select name="pet_breed" id="breedSelect" required>
            <option value="">Select Breed</option>
        </select>



        <label>Symptoms</label>
        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
            <?php include 'symptoms_fieldsets.php'; ?>
        </div>

        <label>Upload Photo</label>
        <input type="file" name="avatar" accept="image/*">

        <button class="btn" type="submit">Save Pet</button>
    </form>

    <div class="back"><a href="petowner_dashboard.php">← Back to Dashboard</a></div>
</div>

<script>
const dogBreeds = <?php echo json_encode($dog_breeds); ?>;
const catBreeds = <?php echo json_encode($cat_breeds); ?>;

document.getElementById('typeSelect').addEventListener('change', function() {
    const breedSelect = document.getElementById('breedSelect');
    breedSelect.innerHTML = '<option value="">Select Breed</option>';
    let breeds = this.value === 'Dog' ? dogBreeds : this.value === 'Cat' ? catBreeds : [];
    breeds.forEach(breed => {
        const option = document.createElement('option');
        option.value = breed;
        option.textContent = breed;
        breedSelect.appendChild(option);
    });
});
</script>
</body>
</html>
