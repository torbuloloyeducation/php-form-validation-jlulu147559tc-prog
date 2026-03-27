<?php
//  Exercise 5: Submission attempt counter , increment sya every POST, if > 0
$attemptCount = isset($_POST['attempt_count']) ? (int)$_POST['attempt_count'] : 0;

// Field values (sticky)  no value pa, empty string muna
$name     = $email    = $website  = $comment  = "";
$gender   = $phone    = "";

// Error holders variables para sa error messages, empty string muna
$nameErr    = $emailErr  = $websiteErr = $commentErr = "";
$genderErr  = $phoneErr  = "";
$passErr    = $confirmErr = $termsErr  = "";

//Output flag
$formValid = false;

//  sanitize 
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// POST handler 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attemptCount++; // Exercise 5: increment on every POST
    
    // Name 
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    // Email 
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Website (Exercise 2: show error + keep value) 
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }


    // Comment 
    if (empty($_POST["comment"])) {
        $commentErr = "Comment is required";
    } else {
        $comment = test_input($_POST["comment"]);
    }

    // Gender 
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }



    //Phone (Exercise 1)
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match('/^[+]?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }

    // Password & Confirm (Exercise 3) for password and confirm pag empty lahi ang mu print then pag na meet ang requiements sa password pero di mag match ang confirm, lahi pud ang mu print nga error, then pag na meet tanan requirements, no error message 
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm_password"] ?? "";
    if (empty($password)) {
        $passErr = "Password is required";
    } elseif (strlen($password) < 8) {
        $passErr = "Password must be at least 8 characters";
    }
    if (empty($confirm)) {
        $confirmErr = "Please confirm your password";
    } elseif ($password !== $confirm && empty($passErr)) {
        $confirmErr = "Passwords do not match";
    }



    // Terms (Exercise 4) errmesage pag di pa na ka input sa checkbox
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }



    // All clear? ma change ang value sa formValid if di wa nay error message
    if (!$nameErr && !$emailErr && !$websiteErr && !$commentErr &&
        !$genderErr && !$phoneErr && !$passErr && !$confirmErr && !$termsErr) {
        $formValid = true;
    }
}

?>


<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PHP Form Validation Lab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>
    /* HTML og CSS*/ 
  /* Reset & base  */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --ink:    #1a1a1a;
    --paper:  #f5f0e8;
    --rule:   #c8bfaa;
    --accent: #c0392b;
    --ok:     #27622a;
    --mono:   'DM Mono', monospace;
    --serif:  'Instrument Serif', Georgia, serif;
    --pad:    1.6rem;
  }

  body {
    background: var(--paper);
    color: var(--ink);
    font-family: var(--mono);
    font-size: 0.875rem;
    line-height: 1.6;
    min-height: 100vh;
    padding: 2rem 1rem 4rem;
  }

  /* Page header  */
  .page-header {
    max-width: 660px;
    margin: 0 auto 2.5rem;
    border-bottom: 2px solid var(--ink);
    padding-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }
  .page-header h1 {
    font-family: var(--serif);
    font-size: 2rem;
    font-style: italic;
    font-weight: 400;
    line-height: 1.1;
  }
  .attempt-badge {
    font-size: 0.7rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #666;
    padding: 0.25rem 0.6rem;
    border: 1px solid var(--rule);
    white-space: nowrap;
  }
  .attempt-badge span {
    color: var(--ink);
    font-weight: 500;
  }

  /* Card */
  .card {
    max-width: 660px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid var(--rule);
    border-top: 3px solid var(--ink);
    padding: var(--pad) calc(var(--pad) * 1.25);
  }

  /* Section label*/
  .section-label {
    font-size: 0.65rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: #888;
    margin: 1.8rem 0 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
  }
  .section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--rule);
  }
  .section-label:first-child { margin-top: 0; }

  /* Field row  */
  .field {
    margin-bottom: 1.1rem;
  }
  .field label {
    display: block;
    font-size: 0.72rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 0.35rem;
    color: #444;
  }
  .required-mark { color: var(--accent); margin-left: 2px; }

  .field input[type="text"],
  .field input[type="email"],
  .field input[type="url"],
  .field input[type="tel"],
  .field input[type="password"],
  .field textarea {
    width: 100%;
    background: var(--paper);
    border: 1px solid var(--rule);
    border-bottom-color: #999;
    padding: 0.55rem 0.75rem;
    font-family: var(--mono);
    font-size: 0.875rem;
    color: var(--ink);
    outline: none;
    transition: border-color 0.15s, background 0.15s;
    -webkit-appearance: none;
    border-radius: 0;
  }
  .field input:focus,
  .field textarea:focus {
    background: #fff;
    border-color: var(--ink);
  }
  .field.has-error input,
  .field.has-error textarea {
    border-color: var(--accent);
    background: #fff5f5;
  }
  .field textarea { resize: vertical; min-height: 90px; }

  /* Message if error */
  .err {
    font-size: 0.72rem;
    color: var(--accent);
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
  }
  .err::before { content: '↳'; }

  /* Radio group*/
  .radio-group {
    display: flex;
    gap: 1.2rem;
    flex-wrap: wrap;
    margin-top: 0.15rem;
  }
  .radio-group label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    text-transform: none;
    letter-spacing: 0;
    font-size: 0.875rem;
    cursor: pointer;
    color: var(--ink);
  }
  .radio-group input[type="radio"] {
    accent-color: var(--ink);
    width: 14px;
    height: 14px;
    cursor: pointer;
  }

  /* Checkbox */
  .checkbox-row {
    display: flex;
    align-items: flex-start;
    gap: 0.55rem;
    margin-top: 0.2rem;
  }
  .checkbox-row input[type="checkbox"] {
    accent-color: var(--ink);
    width: 14px;
    height: 14px;
    margin-top: 3px;
    cursor: pointer;
    flex-shrink: 0;
  }
  .checkbox-row span {
    font-size: 0.82rem;
    color: #555;
    line-height: 1.5;
  }

  /* Submit */
  .submit-row {
    margin-top: 1.8rem;
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  button[type="submit"] {
    background: var(--ink);
    color: var(--paper);
    border: none;
    padding: 0.7rem 2rem;
    font-family: var(--mono);
    font-size: 0.78rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.15s, transform 0.1s;
  }
  button[type="submit"]:hover  { background: #333; }
  button[type="submit"]:active { transform: scale(0.98); }

  /* Success ang output */
  .output {
    max-width: 660px;
    margin: 1.6rem auto 0;
    border: 1px solid #b2d8b4;
    border-top: 3px solid var(--ok);
    background: #f4fbf4;
    padding: var(--pad);
  }
  .output h2 {
    font-family: var(--serif);
    font-size: 1.3rem;
    font-style: italic;
    font-weight: 400;
    color: var(--ok);
    margin-bottom: 1rem;
    border-bottom: 1px solid #b2d8b4;
    padding-bottom: 0.6rem;
  }
  .output table { border-collapse: collapse; width: 100%; }
  .output td {
    padding: 0.4rem 0.6rem;
    font-size: 0.82rem;
    vertical-align: top;
  }
  .output td:first-child {
    font-size: 0.68rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #777;
    width: 38%;
    padding-top: 0.5rem;
  }
  .output tr + tr td { border-top: 1px solid #d8edd8; }
</style>
</head>
<body>

<header class="page-header">
  <h1>PHP Form<br>Validation Lab</h1>
  <?php if ($attemptCount > 0): ?>
  <div class="attempt-badge">
    Submission attempt: <span><?= $attemptCount ?></span>
  </div>
  <?php endif; ?>
</header>

<div class="card">
  <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">

    <!-- Exercise 5: hidden counter -->
    <input type="hidden" name="attempt_count" value="<?= $attemptCount ?>">

    <!--Personal-->
    <div class="section-label">Personal</div>

    <!-- Name -->
    <div class="field <?= $nameErr ? 'has-error' : '' ?>">
      <label>Full name <span class="required-mark">*</span></label>
      <input type="text" name="name" value="<?= $name ?>">
      <?php if ($nameErr): ?><div class="err"><?= $nameErr ?></div><?php endif; ?>
    </div>

    <!-- Email -->
    <div class="field <?= $emailErr ? 'has-error' : '' ?>">
      <label>Email address <span class="required-mark">*</span></label>
      <input type="email" name="email" value="<?= $email ?>">
      <?php if ($emailErr): ?><div class="err"><?= $emailErr ?></div><?php endif; ?>
    </div>

    <!-- Phone (Exercise 1) -->
    <div class="field <?= $phoneErr ? 'has-error' : '' ?>">
      <label>Phone number <span class="required-mark">*</span></label>
      <input type="tel" name="phone" placeholder="+63 912 345 6789" value="<?= $phone ?>">
      <?php if ($phoneErr): ?><div class="err"><?= $phoneErr ?></div><?php endif; ?>
    </div>

    <!-- Gender -->
    <div class="field <?= $genderErr ? 'has-error' : '' ?>">
      <label>Gender <span class="required-mark">*</span></label>
      <div class="radio-group">
        <label><input type="radio" name="gender" value="female" <?= $gender=="female" ? "checked" : "" ?>> Female</label>
        <label><input type="radio" name="gender" value="male"   <?= $gender=="male"   ? "checked" : "" ?>> Male</label>
        <label><input type="radio" name="gender" value="other"  <?= $gender=="other"  ? "checked" : "" ?>> Other</label>
      </div>
      <?php if ($genderErr): ?><div class="err"><?= $genderErr ?></div><?php endif; ?>
    </div>

    <!--Online-->
    <div class="section-label">Online</div>

    <!-- Website (Exercise 2) -->
    <div class="field <?= $websiteErr ? 'has-error' : '' ?>">
      <label>Website <small style="text-transform:none;letter-spacing:0;font-size:0.75em;color:#999">(optional)</small></label>
      <input type="text" name="website" placeholder="https://example.com" value="<?= $website ?>">
      <?php if ($websiteErr): ?><div class="err"><?= $websiteErr ?></div><?php endif; ?>
    </div>

    <!-- Commntss -->
    <div class="field <?= $commentErr ? 'has-error' : '' ?>">
      <label>Comment <span class="required-mark">*</span></label>
      <textarea name="comment"><?= $comment ?></textarea>
      <?php if ($commentErr): ?><div class="err"><?= $commentErr ?></div><?php endif; ?>
    </div>

    <!-- Security (Exercise 3)  -->
    <div class="section-label">Security</div>

    <div class="field <?= $passErr ? 'has-error' : '' ?>">
      <label>Password <span class="required-mark">*</span> <small style="text-transform:none;letter-spacing:0;font-size:0.75em;color:#999">(min 8 chars)</small></label>
      <input type="password" name="password">
      <?php if ($passErr): ?><div class="err"><?= $passErr ?></div><?php endif; ?>
    </div>

    <div class="field <?= $confirmErr ? 'has-error' : '' ?>">
      <label>Confirm password <span class="required-mark">*</span></label>
      <input type="password" name="confirm_password">
      <?php if ($confirmErr): ?><div class="err"><?= $confirmErr ?></div><?php endif; ?>
    </div>

    <!-- Agreement (Exercise 4)-->
    <div class="section-label">Agreement</div>

    <div class="field <?= $termsErr ? 'has-error' : '' ?>">
      <div class="checkbox-row">
        <input type="checkbox" name="terms" id="terms" <?= isset($_POST['terms']) ? 'checked' : '' ?>>
        <label for="terms" style="text-transform:none;letter-spacing:0;font-size:0.82rem;color:#444;cursor:pointer">
          I have read and agree to the <u>terms and conditions</u>
        </label>
      </div>
      <?php if ($termsErr): ?><div class="err"><?= $termsErr ?></div><?php endif; ?>
    </div>

    <div class="submit-row">
      <button type="submit">Submit →</button>
    </div>

  </form>
</div>

<?php if ($formValid): ?>
<div class="output">
  <h2>Submission received</h2>
  <table>
    <tr><td>Name</td>    <td><?= $name ?></td></tr>
    <tr><td>Email</td>   <td><?= $email ?></td></tr>
    <tr><td>Phone</td>   <td><?= $phone ?></td></tr>
    <tr><td>Gender</td>  <td><?= $gender ?></td></tr>
    <?php if ($website): ?>
    <tr><td>Website</td> <td><?= $website ?></td></tr>
    <?php endif; ?>
    <tr><td>Comment</td> <td><?= $comment ?></td></tr>
    <tr><td>Password</td><td><em style="color:#999">not displayed for security</em></td></tr>
    <tr><td>Attempt #</td><td><?= $attemptCount ?></td></tr>
  </table>
</div>
<?php endif; ?>

</body>
</html>