<?php
// Funções de produtos, cursos e receitas

function loadProducts() {
    global $PRODUCTS;
    return $PRODUCTS;
}

function getProduct($productId) {
    $products = loadProducts();
    return $products[$productId] ?? null;
}

function loadCourses() {
    $file = DATA_DIR . '/courses.json';
    
    if (!file_exists($file)) {
        // Inicializa com cursos padrão
        $defaultCourses = [
            'course_1' => [
                'id' => 'course_1',
                'title' => 'Curso Emagrecimento Saudável',
                'description' => 'Aprenda técnicas comprovadas de emagrecimento',
                'thumbnail' => 'assets/images/courses/course-1.jpg',
                'modules' => [
                    [
                        'id' => 'module_1',
                        'title' => 'Introdução ao Emagrecimento',
                        'lessons' => [
                            ['id' => 'lesson_1', 'title' => 'Como funciona o emagrecimento', 'video' => 'video1.mp4', 'duration' => '15:30'],
                            ['id' => 'lesson_2', 'title' => 'Metabolismo e calorias', 'video' => 'video2.mp4', 'duration' => '20:15']
                        ]
                    ],
                    [
                        'id' => 'module_2',
                        'title' => 'Alimentação Balanceada',
                        'lessons' => [
                            ['id' => 'lesson_3', 'title' => 'Macronutrientes essenciais', 'video' => 'video3.mp4', 'duration' => '18:45'],
                            ['id' => 'lesson_4', 'title' => 'Montando seu prato ideal', 'video' => 'video4.mp4', 'duration' => '22:00']
                        ]
                    ]
                ]
            ],
            'course_2' => [
                'id' => 'course_2',
                'title' => 'Dieta Cetogênica Completa',
                'description' => 'Tudo sobre a dieta low carb e cetogênica',
                'thumbnail' => 'assets/images/courses/course-2.jpg',
                'modules' => [
                    [
                        'id' => 'module_1',
                        'title' => 'Fundamentos da Cetose',
                        'lessons' => [
                            ['id' => 'lesson_1', 'title' => 'O que é cetose', 'video' => 'video1.mp4', 'duration' => '12:30'],
                            ['id' => 'lesson_2', 'title' => 'Benefícios da dieta cetogênica', 'video' => 'video2.mp4', 'duration' => '16:20']
                        ]
                    ]
                ]
            ],
            'course_3' => [
                'id' => 'course_3',
                'title' => 'Detox e Desintoxicação',
                'description' => 'Aprenda a desintoxicar seu corpo naturalmente',
                'thumbnail' => 'assets/images/courses/course-3.jpg',
                'modules' => [
                    [
                        'id' => 'module_1',
                        'title' => 'Introdução ao Detox',
                        'lessons' => [
                            ['id' => 'lesson_1', 'title' => 'Por que fazer detox', 'video' => 'video1.mp4', 'duration' => '10:15'],
                            ['id' => 'lesson_2', 'title' => 'Alimentos detox', 'video' => 'video2.mp4', 'duration' => '14:30']
                        ]
                    ]
                ]
            ]
        ];
        
        saveCourses($defaultCourses);
        return $defaultCourses;
    }
    
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

function saveCourses($courses) {
    $file = DATA_DIR . '/courses.json';
    
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    
    $json = json_encode($courses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($file, $json) !== false;
}

function getCourse($courseId) {
    $courses = loadCourses();
    return $courses[$courseId] ?? null;
}

function loadRecipes() {
    $file = DATA_DIR . '/recipes.json';
    
    if (!file_exists($file)) {
        // Inicializa com receitas padrão
        $defaultRecipes = [
            'recipe_1' => [
                'id' => 'recipe_1',
                'title' => 'Salada Detox Completa',
                'description' => 'Salada nutritiva e desintoxicante',
                'category' => 'Saladas',
                'prep_time' => '15 minutos',
                'servings' => '2 porções',
                'calories' => '180 kcal',
                'image' => 'assets/images/recipes/recipe-1.jpg',
                'ingredients' => [
                    '2 xícaras de folhas verdes',
                    '1 cenoura ralada',
                    '1 beterraba ralada',
                    '1 tomate picado',
                    'Azeite e limão a gosto'
                ],
                'instructions' => [
                    'Lave bem todas as folhas',
                    'Rale a cenoura e a beterraba',
                    'Pique o tomate',
                    'Misture tudo em uma tigela',
                    'Tempere com azeite e limão'
                ]
            ],
            'recipe_2' => [
                'id' => 'recipe_2',
                'title' => 'Frango Grelhado com Legumes',
                'description' => 'Proteína magra com vegetais assados',
                'category' => 'Pratos Principais',
                'prep_time' => '30 minutos',
                'servings' => '2 porções',
                'calories' => '320 kcal',
                'image' => 'assets/images/recipes/recipe-2.jpg',
                'ingredients' => [
                    '2 filés de frango',
                    '1 abobrinha',
                    '1 berinjela',
                    '1 pimentão',
                    'Temperos a gosto'
                ],
                'instructions' => [
                    'Tempere o frango',
                    'Corte os legumes',
                    'Grelhe o frango',
                    'Asse os legumes',
                    'Sirva quente'
                ]
            ],
            'recipe_3' => [
                'id' => 'recipe_3',
                'title' => 'Suco Verde Energizante',
                'description' => 'Suco detox rico em nutrientes',
                'category' => 'Bebidas',
                'prep_time' => '10 minutos',
                'servings' => '1 porção',
                'calories' => '95 kcal',
                'image' => 'assets/images/recipes/recipe-3.jpg',
                'ingredients' => [
                    '1 maçã verde',
                    '1 folha de couve',
                    'Suco de 1 limão',
                    '1 pedaço de gengibre',
                    '200ml de água'
                ],
                'instructions' => [
                    'Lave todos os ingredientes',
                    'Corte a maçã',
                    'Bata tudo no liquidificador',
                    'Coe se preferir',
                    'Beba imediatamente'
                ]
            ],
            'recipe_4' => [
                'id' => 'recipe_4',
                'title' => 'Omelete Low Carb',
                'description' => 'Omelete rico em proteínas',
                'category' => 'Café da Manhã',
                'prep_time' => '10 minutos',
                'servings' => '1 porção',
                'calories' => '250 kcal',
                'image' => 'assets/images/recipes/recipe-4.jpg',
                'ingredients' => [
                    '3 ovos',
                    'Queijo mussarela',
                    'Tomate',
                    'Orégano',
                    'Sal e pimenta'
                ],
                'instructions' => [
                    'Bata os ovos',
                    'Adicione os temperos',
                    'Despeje na frigideira',
                    'Adicione queijo e tomate',
                    'Dobre e sirva'
                ]
            ],
            'recipe_5' => [
                'id' => 'recipe_5',
                'title' => 'Salmão com Aspargos',
                'description' => 'Prato cetogênico rico em ômega 3',
                'category' => 'Pratos Principais',
                'prep_time' => '25 minutos',
                'servings' => '2 porções',
                'calories' => '380 kcal',
                'image' => 'assets/images/recipes/recipe-5.jpg',
                'ingredients' => [
                    '2 filés de salmão',
                    '1 maço de aspargos',
                    'Azeite',
                    'Limão',
                    'Temperos'
                ],
                'instructions' => [
                    'Tempere o salmão',
                    'Limpe os aspargos',
                    'Asse o salmão',
                    'Grelhe os aspargos',
                    'Finalize com limão'
                ]
            ],
            'recipe_6' => [
                'id' => 'recipe_6',
                'title' => 'Abacate Recheado',
                'description' => 'Lanche cetogênico nutritivo',
                'category' => 'Lanches',
                'prep_time' => '5 minutos',
                'servings' => '1 porção',
                'calories' => '280 kcal',
                'image' => 'assets/images/recipes/recipe-6.jpg',
                'ingredients' => [
                    '1 abacate',
                    '2 ovos cozidos',
                    'Sal e pimenta',
                    'Azeite',
                    'Salsinha'
                ],
                'instructions' => [
                    'Corte o abacate ao meio',
                    'Retire o caroço',
                    'Amasse os ovos',
                    'Recheie o abacate',
                    'Tempere e sirva'
                ]
            ],
            'recipe_7' => [
                'id' => 'recipe_7',
                'title' => 'Água Detox de Frutas',
                'description' => 'Água saborizada desintoxicante',
                'category' => 'Bebidas',
                'prep_time' => '5 minutos',
                'servings' => '1 litro',
                'calories' => '40 kcal',
                'image' => 'assets/images/recipes/recipe-7.jpg',
                'ingredients' => [
                    '1 litro de água',
                    '1 limão',
                    'Folhas de hortelã',
                    'Rodelas de pepino',
                    'Gelo'
                ],
                'instructions' => [
                    'Corte o limão em rodelas',
                    'Adicione à água',
                    'Acrescente hortelã e pepino',
                    'Deixe na geladeira por 2 horas',
                    'Sirva gelado'
                ]
            ],
            'recipe_8' => [
                'id' => 'recipe_8',
                'title' => 'Smoothie Detox',
                'description' => 'Vitamina desintoxicante e energizante',
                'category' => 'Bebidas',
                'prep_time' => '5 minutos',
                'servings' => '1 porção',
                'calories' => '120 kcal',
                'image' => 'assets/images/recipes/recipe-8.jpg',
                'ingredients' => [
                    '1 banana',
                    '1 xícara de espinafre',
                    '1/2 xícara de abacaxi',
                    '200ml de água de coco',
                    '1 colher de chia'
                ],
                'instructions' => [
                    'Lave o espinafre',
                    'Corte as frutas',
                    'Bata tudo no liquidificador',
                    'Adicione a chia',
                    'Sirva imediatamente'
                ]
            ]
        ];
        
        saveRecipes($defaultRecipes);
        return $defaultRecipes;
    }
    
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

function saveRecipes($recipes) {
    $file = DATA_DIR . '/recipes.json';
    
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    
    $json = json_encode($recipes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($file, $json) !== false;
}

function getRecipe($recipeId) {
    $recipes = loadRecipes();
    return $recipes[$recipeId] ?? null;
}

function getRecipesByCategory($category = null) {
    $recipes = loadRecipes();
    
    if ($category === null) {
        return $recipes;
    }
    
    return array_filter($recipes, function($recipe) use ($category) {
        return $recipe['category'] === $category;
    });
}

function getRecipeCategories() {
    $recipes = loadRecipes();
    $categories = [];
    
    foreach ($recipes as $recipe) {
        if (!in_array($recipe['category'], $categories)) {
            $categories[] = $recipe['category'];
        }
    }
    
    return $categories;
}
