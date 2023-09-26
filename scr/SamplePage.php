<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php

$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

$database = mysqli_select_db($connection, DB_DATABASE);

VerifyDataTable($connection, DB_DATABASE);

$data_nome = htmlentities($_POST['DATA_NOME']);
$data_idade = htmlentities($_POST['DATA_IDADE']);
$data_peso = htmlentities($_POST['DATA_PESO']);
$data_altura = htmlentities($_POST['DATA_ALTURA']);

if (strlen($data_nome) || strlen($data_idade) || strlen($data_peso) || strlen($data_altura)) {
    AddData($connection, $data_nome, $data_idade, $data_peso, $data_altura);
}
?>

<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
    <h2>Add Data</h2>
    <table border="0">
        <tr>
            <td>Nome</td>
            <td>Idade</td>
            <td>Peso</td>
            <td>Altura (m)</td>
        </tr>
        <tr>
            <td>
                <input type="text" name="DATA_NOME" maxlength="45" size="30" />
            </td>
            <td>
                <input type="text" name="DATA_IDADE" maxlength="100" size="60" />
            </td>
            <td>
                <input type="text" name="DATA_PESO" maxlength="20" size="20" />
            </td>
            <td>
                <input type="text" name="DATA_ALTURA" maxlength="90" size="60" />
            </td>
            <td>
                <input type="submit" value="Add Data" />
            </td>
        </tr>
    </table>
</form>


<table border="1" cellpadding="2" cellspacing="2">
    <tr>
        <td>ID</td>
        <td>NOME</td>
        <td>IDADE</td>
        <td>PESO</td>
        <td>ALTURA (m)</td>
    </tr>

    <?php
    $result = mysqli_query($connection, "SELECT * FROM DATA");

    while ($query_data = mysqli_fetch_row($result)) {
        echo "<tr>";
        echo "<td>", $query_data[0], "</td>",
        "<td>", $query_data[1], "</td>",
        "<td>", $query_data[2], "</td>",
        "<td>", $query_data[3], "</td>",
        "<td>", $query_data[4], "</td>";
        echo "</tr>";
    }
    ?>

</table>

<?php

mysqli_free_result($result);
mysqli_close($connection);

?>

</body>
</html>

<?php

/* adiciona dados na tabela */
function AddData($connection, $name, $email, $phone, $address) {
   $n = mysqli_real_escape_string($connection, $nome);
   $i = mysqli_real_escape_string($connection, $idade);
   $p = mysqli_real_escape_string($connection, $peso);
   $a = mysqli_real_escape_string($connection, $altura);

    $query = "INSERT INTO DATA (NOME, IDADE, PESO, ALTURA) VALUES ('$n', '$e', '$p', '$a');";

    if (!mysqli_query($connection, $query)) echo("<p>Error adding data.</p>");
}

function VerifyDataTable($connection, $dbName) {
    if (!TableExists("DATA", $connection, $dbName)) {
        $query = "CREATE TABLE DATA (
            ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            NOME VARCHAR(45),
            IDADE VARCHAR(2),
            PESO int(4),
            ALTURA float(3)
          )"; 

        if (!mysqli_query($connection, $query)) echo("<p>Error creating DATA table.</p>");
    }
}

/* Check whether a table exists. */
function TableExists($tableName, $connection, $dbName) {
    $t = mysqli_real_escape_string($connection, $tableName);
    $d = mysqli_real_escape_string($connection, $dbName);

    $checktable = mysqli_query($connection,
        "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

    if (mysqli_num_rows($checktable) > 0) return true;

    return false;
}
?>
