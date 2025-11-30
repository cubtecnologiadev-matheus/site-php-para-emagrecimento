<?php
// Funções de autenticação

function registerUser($name, $email, $cpf, $phone, $password) {
    $users = loadUsers();
    
    $userId = uniqid('user_');
    
    $user = [
        'id' => $userId,
        'name' => $name,
        'email' => $email,
        'cpf' => $cpf,
        'phone' => $phone,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
        'courses' => [],
        'recipes' => [],
        'purchases' => []
    ];
    
    $users[$userId] = $user;
    
    if (saveUsers($users)) {
        return $user;
    }
    
    return false;
}

function loginUser($cpf, $password) {
    $users = loadUsers();
    
    foreach ($users as $user) {
        if ($user['cpf'] === $cpf && password_verify($password, $user['password'])) {
            return $user;
        }
    }
    
    return false;
}

function userExists($cpf) {
    $users = loadUsers();
    
    foreach ($users as $user) {
        if ($user['cpf'] === $cpf) {
            return true;
        }
    }
    
    return false;
}

function getUserById($userId) {
    $users = loadUsers();
    return $users[$userId] ?? null;
}

function getUserByCPF($cpf) {
    $users = loadUsers();
    
    foreach ($users as $user) {
        if ($user['cpf'] === $cpf) {
            return $user;
        }
    }
    
    return null;
}

function updateUser($userId, $data) {
    $users = loadUsers();
    
    if (isset($users[$userId])) {
        $users[$userId] = array_merge($users[$userId], $data);
        return saveUsers($users);
    }
    
    return false;
}

function addCourseToUser($userId, $courseId) {
    $users = loadUsers();
    
    if (isset($users[$userId])) {
        if (!in_array($courseId, $users[$userId]['courses'])) {
            $users[$userId]['courses'][] = $courseId;
            return saveUsers($users);
        }
    }
    
    return false;
}

function addRecipeToUser($userId, $recipeId) {
    $users = loadUsers();
    
    if (isset($users[$userId])) {
        if (!in_array($recipeId, $users[$userId]['recipes'])) {
            $users[$userId]['recipes'][] = $recipeId;
            return saveUsers($users);
        }
    }
    
    return false;
}

function addPurchaseToUser($userId, $purchaseData) {
    $users = loadUsers();
    
    if (isset($users[$userId])) {
        $users[$userId]['purchases'][] = $purchaseData;
        return saveUsers($users);
    }
    
    return false;
}

function getUserPurchases($userId) {
    $user = getUserById($userId);
    return $user['purchases'] ?? [];
}

function getUserCourses($userId) {
    $user = getUserById($userId);
    $courses = loadCourses();
    
    $userCourses = [];
    foreach ($user['courses'] ?? [] as $courseId) {
        if (isset($courses[$courseId])) {
            $userCourses[] = $courses[$courseId];
        }
    }
    
    return $userCourses;
}

function getUserRecipes($userId) {
    $user = getUserById($userId);
    $recipes = loadRecipes();
    
    $userRecipes = [];
    foreach ($user['recipes'] ?? [] as $recipeId) {
        if (isset($recipes[$recipeId])) {
            $userRecipes[] = $recipes[$recipeId];
        }
    }
    
    return $userRecipes;
}

function countUserCourses($userId) {
    $user = getUserById($userId);
    return count($user['courses'] ?? []);
}

function countUserRecipes($userId) {
    $user = getUserById($userId);
    return count($user['recipes'] ?? []);
}

// Carrega usuários do arquivo JSON
function loadUsers() {
    $file = DATA_DIR . '/users.json';
    
    if (!file_exists($file)) {
        return [];
    }
    
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

// Salva usuários no arquivo JSON
function saveUsers($users) {
    $file = DATA_DIR . '/users.json';
    
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($file, $json) !== false;
}
