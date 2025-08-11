<?php
function get_user_count($connection) {
    $sql = "SELECT COUNT(*) AS user_count FROM users";
    $count_result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($count_result) > 0) {
        $row = mysqli_fetch_assoc($count_result);
        return (int) $row['user_count'];
    }
    return 0;
}

function get_sports($connection) {
    $sports_array = [];
    $fetch_sports = "SELECT sport_id,sport_name FROM sports";
    $sports_result = mysqli_query($connection, $fetch_sports);
    if (mysqli_num_rows($sports_result) > 0) {
        while ($row = mysqli_fetch_assoc($sports_result)) {
            $sports_array[] = $row;
        }
    }
    return $sports_array;
}

function post_sport($connection, $newSport) {
    $sql = "INSERT INTO sports(sport_name) VALUES(?)";
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt === false) {
        die("Error preparing statement : " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "s", $newSport);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function get_niveaux($connection) {
    $niveaux_array = [];
    $fetch_niveaux = "SELECT niveau_id,niveau_name FROM niveaux";
    $niveaux_result = mysqli_query($connection, $fetch_niveaux);
    if (mysqli_num_rows($niveaux_result) > 0) {
        while ($row = mysqli_fetch_assoc($niveaux_result)) {
            $niveaux_array[] = $row;
        }
    }
    return $niveaux_array;
}

function get_user($connection, $email) {
    $fetch_user = "SELECT * FROM users WHERE email='$email'";
    $user_result = mysqli_query($connection, $fetch_user);
    $user = mysqli_fetch_assoc($user_result);
    return $user;
}

function post_user($connection, $nom, $prenom, $dob, $email, $region) {
    $sql = "INSERT INTO users(nom,prenom,date_naissance,email,region) VALUES(?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "sssss", $nom, $prenom, $dob, $email, $region);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function post_inscription($connection, $user_id, $sport_id, $niveau_id) {
    $sql = "INSERT INTO inscriptions(user_id,sport_id,niveau_id) VALUES(?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt === false) {
        die("Error preparing statement:" . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "sss", $user_id, $sport_id, $niveau_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function get_images($connection) {
    $images_array = [];
    $sql = "SELECT * FROM images";
    $images_result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($images_result) > 0) {
        while ($row = mysqli_fetch_assoc($images_result)) {
            $images_array[] = $row;
        }
    }
    return $images_array;
}

function get_regions($connection) {
    $regions_array = [];
    $sql = "SELECT DISTINCT region FROM users ORDER BY region ASC";
    $regions_result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($regions_result) > 0) {
        while ($row = mysqli_fetch_assoc($regions_result)) {
            $regions_array[] = $row['region'];
        }
    }
    return $regions_array;
}

function get_partners($connection, $filterInput) {
    $filters = [];
    $sql = "SELECT users.nom,users.prenom,users.region,
            niveaux.niveau_name AS niveau,
            sports.sport_name AS sport FROM users
            JOIN inscriptions ON users.user_id=inscriptions.user_id
            JOIN niveaux ON inscriptions.niveau_id=niveaux.niveau_id
            JOIN sports ON inscriptions.sport_id=sports.sport_id WHERE 1";
    $sport = null;
    $niveau = null;
    $region = null;

    if (!empty($filterInput['sport'])) {
        $sport = mysqli_real_escape_string($connection, $filterInput['sport']);
        $filters[] = "inscriptions.sport_id = '" . $sport . "'";
    }

    if (!empty($filterInput['niveau'])) {
        $niveau = mysqli_real_escape_string($connection, $filterInput['niveau']);
        $filters[] = "inscriptions.niveau_id = '" . $niveau . "'";
    }

    if (!empty($filterInput['region'])) {
        $region = mysqli_real_escape_string($connection, $filterInput['region']);
        $filters[] = "users.region = '" . $region . "'";
    }

    if (!empty($filters)) {
        $sql .= " AND " . implode(" AND ", $filters);
    }

    $partners_result = mysqli_query($connection, $sql);
    return $partners_result;
}

?>