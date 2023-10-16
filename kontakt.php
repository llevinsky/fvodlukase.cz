<?php
session_start();
const TITLE = 'FVodLukáše - Kontakt';
?>
<?php require 'components/header.php'; ?>

<link rel="stylesheet" href="components/css/kontakt.css">
<link rel="stylesheet" href="components/css/remove.css">

<div class="container-sm">
    <main id="kontakt">
        <h1>Kontakt</h1>
        <p>Máte-li jakékoli dotazy ohledně fotovoltaických elektráren, jsem tu pro vás. Jsem připraven poskytnout odpovědi na vaše dotazy a sdílet své odborné znalosti. Kontaktujte mne prostřednictvím formuláře a já se vám ozvu co nejdříve.</p>
    </main>
    <form id="form" action="process/processContact.php" method="post" enctype="multipart/form-data">
        <div class="input-control">
            <label for="firstname">Jméno*</label>
            <input id="firstname" name="firstname" minlength="2" type="text" value="<?php echo isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] : ''; ?>" required>
            <div class="error">
                <?php echo isset($_SESSION['error_messages']['firstname']) ? $_SESSION['error_messages']['firstname'] : ''; ?>
            </div>
        </div>
        <div class="input-control">
            <label for="lastname">Příjmení*</label>
            <input id="lastname" name="lastname" minlength="2" type="text" value="<?php echo isset($_SESSION['user_lastname']) ? $_SESSION['user_lastname'] : ''; ?>" required>
            <div class="error">
                <?php echo isset($_SESSION['error_messages']['lastname']) ? $_SESSION['error_messages']['lastname'] : ''; ?>
            </div>
        </div>
        <div class="input-control">
            <label for="email">Email*</label>
            <input id="email" name="email" type="email" value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>" required>
            <div class="error">
                <?php echo isset($_SESSION['error_messages']['email']) ? $_SESSION['error_messages']['email'] : ''; ?>
            </div>
        </div>
        <div class="input-control">
            <label for="phone">Telefon*</label>
            <input id="phone" name="phone" type="text" value="+420 " required>
            <div class="error">
                <?php echo isset($_SESSION['error_messages']['phone']) ? $_SESSION['error_messages']['phone'] : ''; ?>
            </div>
        </div>
        <div class="input-control">
            <label for="msg">Zpráva*</label>
            <textarea id="msg" name="msg" rows="6" required></textarea>
            <div class="error">
                <?php echo isset($_SESSION['error_messages']['msg']) ? $_SESSION['error_messages']['msg'] : ''; ?>
            </div>
        </div>
        <div class="input-control">
            <label for="file-upload" class="custom-file-upload">
                Nahrát soubory
            </label>
            <input id="file-upload" type="file" accept=".pdf, .jpg, .jpeg, .png, .doc, .docx, .rar, .zip" name="file[]" multiple>
            <div class="error">
                <?php echo isset($_SESSION['error_messages']['file']) ? $_SESSION['error_messages']['file'] : ''; ?>
            </div>
        </div>
        <div id="file-upload-container"></div>
        <button name="contact" type="submit">Odeslat formulář</button>
        <?php unset($_SESSION['error_messages']); ?>
    </form>
</div>

<script src="components/js/contactFormValidation.js"></script>
<script src="components/js/remove.js"></script>
<script>
    // Hide the error message when interacting with the form or uploading a file
    document.querySelectorAll('#form input, #form textarea, #file-upload').forEach(function(element) {
        element.addEventListener('input', function() {
            var errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(function(errorMessage) {
                errorMessage.style.display = 'none';
            });
        });
    });
</script>

<?php require 'components/footer.php'; ?>