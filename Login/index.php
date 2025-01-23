<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<form>
  <!-- Email input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <label class="form-label" for="form2Example1">Email address</label>
    <input type="email" id="form2Example1" class="form-control" />
  </div>

  <!-- Password input -->
  <div data-mdb-input-init class="form-outline mb-4">
  <label class="form-label" for="form2Example2">Password</label>
    <input type="password" id="form2Example2" class="form-control" />
  </div>

  <!-- 2 column grid layout for inline styling -->
  <div class="row mb-4">
    <div class="col d-flex justify-content-center align-items-center">
      <!-- Checkbox -->
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
        <label class="form-check-label" for="form2Example31"> Remember me </label>
      </div>
    </div>

    <div class="col">
      <!-- Simple link -->
      <a href="/Austin'sIMS-POS/Login/forgotpass.php">Forgot password?</a>
    </div>
  </div>

  <!-- Submit button -->
  <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign in</button>
  </div>
</form>
</body>
</html>