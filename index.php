<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Personal Finances Home to MySQL</title>
        <meta name="author" content="bushikot@gmail.com">
        <meta name="description" content="Personal Finances Home CSV file to MySQL import script">
    </head>
	<body>
		<form action="" method="post" enctype="multipart/form-data">
			<p>Select exported CSV that uses ";" as separator</p>
			<input type="file" name="pfh">
			<input type="submit">
		</form>

<?php
if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_FILES["pfh"])) {
	pfh_to_mysql($_FILES['pfh']['tmp_name']);
	echo "Script succesfully executed!";
}

function pfh_to_mysql($filename) {
	try {
		$db_host = 'localhost';
		$db_port = '8889';
		$db_name = 'pfh';
		$db_user = 'root';
		$user_pw = 'root';

		$dbh = new PDO("mysql:host={$db_host};port={$db_port};dbname={$db_name}", $db_user, $user_pw);  
		$dbh->exec("set names utf8");
	}
	catch (PDOException $err) {  
		echo "Database connection error!";
		die();
	}

	if (($handle = fopen($filename, 'r')) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
			print_r($data);
			echo "<br>";

			$stmt = $dbh->prepare("
				INSERT IGNORE INTO account (caption)
				VALUES (:caption)
			");

				$stmt->execute(array(
					':caption' => $data[9]
				));

			$stmt = $dbh->prepare("
				INSERT IGNORE INTO description (caption)
				VALUES (:caption)
			");

				$stmt->execute(array(
					':caption' => $data[1]
				));

			$stmt = $dbh->prepare("
				INSERT IGNORE INTO tag (caption)
				VALUES (:caption)
			");

				$stmt->execute(array(
					':caption' => $data[8]
				));

			$stmt = $dbh->prepare("
				INSERT IGNORE INTO custom_field (caption)
				VALUES (:caption)
			");

				$stmt->execute(array(
					':caption' => $data[10]
				));

				$stmt->execute(array(
					':caption' => $data[11]
				));

				$stmt->execute(array(
					':caption' => $data[12]
				));

			$stmt = $dbh->prepare("
				INSERT IGNORE INTO family (name)
				VALUES (:name)
			");

				$stmt->execute(array(
					':name' => $data[7]
				));

			$stmt = $dbh->prepare("
				INSERT IGNORE INTO notes (notes_text, text_hash)
				VALUES (:notes_text, :text_hash)
			");

				$stmt->execute(array(
					':notes_text' => $data[5],
					':text_hash' => md5($data[5])
				));

			$stmt = $dbh->prepare("
				INSERT IGNORE INTO category (account_id, caption)
				VALUES ((SELECT account_id FROM account WHERE caption = :account_caption), :caption)
			");

				$stmt->execute(array(
					':account_caption' => $data[9],
					':caption' => $data[6]
				));

			$stmt = $dbh->prepare("
				INSERT IGNORE INTO transaction (tr_date, quantity, amount, price, description_id, family_id, tag_id, account_id, notes_id, category_id, custom_field_1, custom_field_2, custom_field_3)
				VALUES (
					STR_TO_DATE(:date, '%d.%m.%Y'),
					:quantity,
					:amount,
					:price,
					(SELECT description_id FROM description WHERE caption = :description_caption),
					(SELECT family_id FROM family WHERE name = :family_name),
					(SELECT tag_id FROM tag WHERE caption = :tag_caption),
					(SELECT account_id FROM account WHERE caption = :account_caption),
					(SELECT notes_id FROM notes WHERE notes_text = :notes_text),
					(SELECT category_id FROM category WHERE caption = :category_caption AND account_id = (SELECT account_id FROM account WHERE caption = :account_caption)),
					(SELECT custom_field_id FROM custom_field WHERE caption = :custom_field_1_caption),
					(SELECT custom_field_id FROM custom_field WHERE caption = :custom_field_2_caption),
					(SELECT custom_field_id FROM custom_field WHERE caption = :custom_field_3_caption)
				)
			");

				$stmt->execute(array(
					':date' => $data[0],
					':quantity' => $data[2],
					':amount' => $data[3],
					':price' => $data[4],
					':description_caption' => $data[1],
					':family_name' => $data[7],
					':tag_caption' => $data[8],
					':account_caption' => $data[9],
					':notes_text' => $data[5],
					':category_caption' => $data[6],
					':custom_field_1_caption' => $data[10],
					':custom_field_2_caption' => $data[11],
					':custom_field_3_caption' => $data[12]
				));
		}
		fclose($handle);
	}
}
?>

	</body>
</html> 