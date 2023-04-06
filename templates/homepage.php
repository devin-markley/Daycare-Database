<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="templates/homepage.css">
	<title>Homepage</title>
</head>
<body>
	<div class="nav-bar">
		<div class="brand-name">
			<p>Western Slope Daycare Management</p>
		</div>
		<div class="nav-buttons">
			<div class="provider-buttons">
				<?= $provider_buttons ?>
			</div>
		</div>	
	</div>
	<script>
		$(document).ready(function() {
  			$(".providerbutt").click(function() {
    		var providerId = $(this).data("provider-id");
    		window.location.href = "index.php?page=provider_page&provider_id=" + providerId;
  			});
		});
	</script>

</body>
</html>
