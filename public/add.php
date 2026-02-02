<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_login();

<<<<<<< HEAD
$errors = []; // Error container

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token']);
    
    // 1. Get & Validate Form Data
=======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token']);
    
    // 1. Get Form Data
>>>>>>> 091c91b221d63451da86c33f35aef85c40e8278c
    $name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $start = $_POST['start_date'];
    $package = (int)$_POST['package'];
    
<<<<<<< HEAD
    // Server-side Validation
    if (empty($name)) $errors[] = "Member name is required.";
    if (empty($phone)) $errors[] = "Phone number is required.";
    if (empty($start)) $errors[] = "Start date is required.";

    // 2. Handle Photo Upload (With Security)
    $photo = "default.png";
    
    // Only proceed if no text errors and a file was uploaded
    if (empty($errors) && !empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        
        // --- SECURITY FIX: Whitelist Extensions ---
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $photoName = time() . "_" . uniqid() . "." . $ext;
            $destination = "uploads/" . $photoName;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $photo = $photoName;
            } else {
                $errors[] = "Failed to save the uploaded photo.";
            }
        } else {
            $errors[] = "Invalid file type. Only JPG, PNG, and GIF allowed.";
        }
    }

    // 3. Save to Database if No Errors
    if (empty($errors)) {
        // Calculate End Date
        $end = date('Y-m-d', strtotime("$start + $package months"));

        $stmt = $pdo->prepare("INSERT INTO members (full_name, phone, start_date, end_date, photo) VALUES (?,?,?,?,?)");
        $stmt->execute([$name, $phone, $start, $end, $photo]);
        
        header("Location: dashboard.php"); 
        exit;
    }
=======
    // 2. Calculate End Date (Start + X Months)
    $end = date('Y-m-d', strtotime("$start + $package months"));
    
    // 3. Handle Cute Photo Upload
    $photo = "default.png";
    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
    }

    // 4. Save to Database
    $stmt = $pdo->prepare("INSERT INTO members (full_name, phone, start_date, end_date, photo) VALUES (?,?,?,?,?)");
    $stmt->execute([$name, $phone, $start, $end, $photo]);
    
    header("Location: dashboard.php"); 
    exit;
>>>>>>> 091c91b221d63451da86c33f35aef85c40e8278c
}
?>
<?php include '../includes/header.php'; ?>

<!-- Style Wrapper -->
<div class="content-card" style="max-width: 480px; margin: 20px auto; padding: 40px;">
    
    <div style="text-align: center; margin-bottom: 25px;">
        <h2 style="color: var(--primary-pink); margin: 0; font-size: 24px;">New Registration 💖</h2>
        <p style="color: #999; font-size: 14px;">Fill in the details to join the gym</p>
    </div>

<<<<<<< HEAD
    <!-- Error Display -->
    <?php if (!empty($errors)): ?>
        <div style="background: #fff0f3; color: #d32f2f; padding: 15px; border-radius: 15px; margin-bottom: 20px; border: 1px solid #ffcdd2;">
            <?php foreach($errors as $err): ?>
                <p style="margin: 0; font-size: 13px;">• <?php echo h($err); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

=======
>>>>>>> 091c91b221d63451da86c33f35aef85c40e8278c
    <form method="POST" enctype="multipart/form-data">
        <!-- Security Token -->
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

        <!-- Profile Photo -->
        <div style="margin-bottom: 20px;">
            <label>Profile Photo</label>
            
            <div style="margin-bottom: 10px;">
                <button type="button" id="start-camera" style="background: var(--light-pink); color: var(--primary-pink); border: none; padding: 8px 15px; border-radius: 10px; cursor: pointer; font-size: 14px;">
                    📸 Use Camera
                </button>
            </div>

            <!-- Camera Interface (Hidden by default) -->
            <div id="camera-container" style="display: none; margin-bottom: 15px; text-align: center;">
                <video id="video" width="320" height="240" autoplay style="border-radius: 15px; border: 2px solid var(--primary-pink); object-fit: cover;"></video>
                <div style="margin-top: 10px;">
                    <button type="button" id="click-photo" class="btn btn-main" style="padding: 8px 20px; font-size: 14px;">Capture Photo</button>
                </div>
            </div>

            <!-- Hidden Canvas -->
            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>

<<<<<<< HEAD
            <input type="file" name="photo" id="photo-input" accept="image/png, image/jpeg, image/gif" style="border: 2px dashed var(--light-pink); padding: 15px; background: #fffcfd; width: 100%; border-radius: 15px;">
=======
            <input type="file" name="photo" id="photo-input" accept="image/*" style="border: 2px dashed var(--light-pink); padding: 15px; background: #fffcfd; width: 100%; border-radius: 15px;">
>>>>>>> 091c91b221d63451da86c33f35aef85c40e8278c
        </div>

        <script>
            let camera_button = document.querySelector("#start-camera");
            let video = document.querySelector("#video");
            let click_button = document.querySelector("#click-photo");
            let canvas = document.querySelector("#canvas");
            let photo_input = document.querySelector("#photo-input");
            let camera_container = document.querySelector("#camera-container");
            let stream = null;

            camera_button.addEventListener('click', async function() {
                if (camera_container.style.display === 'none') {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                        video.srcObject = stream;
                        camera_container.style.display = 'block';
                        camera_button.textContent = "❌ Close Camera";
                    } catch (error) {
                        alert("Cannot access camera: " + error.message);
                    }
                } else {
                    stopCamera();
                }
            });

            click_button.addEventListener('click', function() {
                if (!stream) return;

                // Draw video to canvas
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Convert to file
                canvas.toBlob(function(blob) {
                    let file = new File([blob], "camera_capture_" + Date.now() + ".png", { type: "image/png" });
                    let container = new DataTransfer();
                    container.items.add(file);
                    photo_input.files = container.files;
                    
                    // Visual feedback
                    stopCamera();
                    alert("Photo captured successfully!");
                }, 'image/png');
            });

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                camera_container.style.display = 'none';
                camera_button.textContent = "📸 Use Camera";
            }
        </script>

        <!-- Full Name -->
        <div>
            <label>Full Name</label>
<<<<<<< HEAD
            <input type="text" name="full_name" placeholder="e.g. Barbie Doe" value="<?php echo h($_POST['full_name'] ?? ''); ?>" required>
=======
            <input type="text" name="full_name" placeholder="e.g. Barbie Doe" required>
>>>>>>> 091c91b221d63451da86c33f35aef85c40e8278c
        </div>

        <!-- Phone -->
        <div>
            <label>Phone Number</label>
<<<<<<< HEAD
            <input type="text" name="phone" placeholder="e.g. 0123456789" value="<?php echo h($_POST['phone'] ?? ''); ?>" required>
=======
            <input type="text" name="phone" placeholder="e.g. 0123456789" required>
>>>>>>> 091c91b221d63451da86c33f35aef85c40e8278c
        </div>

        <!-- Row for Dates/Packages -->
        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Start Date</label>
<<<<<<< HEAD
                <input type="date" name="start_date" value="<?php echo h($_POST['start_date'] ?? date('Y-m-d')); ?>" required>
=======
                <input type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>" required>
>>>>>>> 091c91b221d63451da86c33f35aef85c40e8278c
            </div>
            <div style="flex: 1;">
                <label>Package</label>
                <select name="package">
<<<<<<< HEAD
                    <option value="1" <?php echo (($_POST['package']??'') == 1) ? 'selected' : ''; ?>>1 Month</option>
                    <option value="3" <?php echo (($_POST['package']??'') == 3) ? 'selected' : ''; ?>>3 Months</option>
                    <option value="6" <?php echo (($_POST['package']??'') == 6) ? 'selected' : ''; ?>>6 Months</option>
                    <option value="12" <?php echo (($_POST['package']??'') == 12) ? 'selected' : ''; ?>>1 Year</option>
=======
                    <option value="1">1 Month</option>
                    <option value="3">3 Months</option>
                    <option value="6">6 Months</option>
                    <option value="12">1 Year</option>
>>>>>>> 091c91b221d63451da86c33f35aef85c40e8278c
                </select>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-main" style="width: 100%; padding: 15px; font-size: 16px; margin-top: 10px;">
            Register Member ✨
        </button>

        <a href="dashboard.php" style="display: block; text-align: center; margin-top: 20px; color: #999; text-decoration: none; font-size: 13px;">
            Go Back to Dashboard
        </a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>