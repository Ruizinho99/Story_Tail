<?php
include 'db_connection.php';
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Iniciar sessão (caso ainda não tenha sido iniciada)
session_start();

// Verificar se o usuário está logado
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    // Se estiver logado, inclui o 'header.html'
    include('header.html');
} else {
    // Se não estiver logado, inclui o 'sl_header.html'
    include('sl_header.html');
}


// Função para verificar se o livro é acessível
function canAccessBook($book, $user)
{
    if ($book['access_level'] == 1) {
        if ($user['user_type_id'] == 1) {
            return true; // Usuário premium pode acessar
        } else {
            return false; // Usuário não premium não pode acessar
        }
    }
    return true; // Se for público (0), todos podem acessar
}

// Função para obter livros com base na pesquisa e categoria
function getBooks($category, $searchTerm)
{
    global $conn;

    $searchSql = "";
    if ($searchTerm) {
        $searchSql = " AND description LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
    }

    if ($category == 'New Books') {
        $sql = "SELECT id, title, description, cover_url, access_level FROM books WHERE is_active = 1" . $searchSql . " ORDER BY added_at DESC"; // Mais recentes primeiro
    } else {
        $sql = "SELECT id, title, description, cover_url, access_level FROM books WHERE is_active = 1" . $searchSql . " ORDER BY RAND()"; // Ordem aleatória
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        return $books;
    } else {
        return [];
    }
}

// Exemplo de usuário logado
$user = ['user_type_id' => 2];  // Exemplo de um usuário gratuito (Free)

// Obter termo de busca
$searchTerm = isset($_GET['search']) ? $_GET['search'] : "";

// Verificar categoria selecionada
$category = isset($_GET['category']) ? $_GET['category'] : "New Books";
$books = getBooks($category, $searchTerm);
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Livros com Filtros</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      max-width: 250px; /* Reduz o tamanho dos cards */
      margin: 0 auto; /* Centraliza os cards */
      border: 2px solid #E1700F; /* Borda com a cor desejada */
      border-radius: 8px; /* Bordas arredondadas para um visual mais suave */
      box-shadow: 2px 4px 8px #E1700F; /* Sombra suave ao redor do card */
    }

    .card-img-top {
      height: 250px; /* Define uma altura fixa para a imagem */
      width: 100%; /* A largura da imagem será sempre 100% da largura do card */
      object-fit: cover; /* Preenche o espaço da imagem sem distorção e sem corte */
      border-top-left-radius: 8px; /* Bordas arredondadas no topo da imagem */
      border-top-right-radius: 8px; /* Bordas arredondadas no topo da imagem */
    }

    .card-body {
      padding: 0.5rem; /* Reduz o padding dentro do card */
    }

    .card-title {
      font-size: 1rem; /* Ajusta o tamanho do título */
      font-weight: bold;
    }

    .card-text {
      font-size: 0.875rem; /* Ajusta o tamanho da descrição */
      height: 3.6rem; /* Limita a altura da descrição (aproximadamente 3 linhas) */
      overflow: hidden; /* Oculta qualquer texto que ultrapasse o limite */
    }

    /* Estilos para a responsividade da sidebar */
    @media (max-width: 768px) {
      .aside-sidebar {
        margin-bottom: 2rem;
      }
    }
  </style>
</head>

<body>

  <!-- Main Content -->
  <div class="container my-5">
    <div class="row">
      <!-- Sidebar de Filtros -->
      <aside class="col-md-2 col-sm-3 col-12 mb-4 aside-sidebar">
        <h5>Filtros</h5>

        <!-- Filtro por categoria -->
        <div class="mb-3">
          <h6>Categorias</h6>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="New Books" id="newBooks" <?= $category == 'New Books' ? 'checked' : '' ?>>
            <label class="form-check-label" for="newBooks">Novos Livros</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="Most Popular" id="mostPopular" <?= $category == 'Most Popular' ? 'checked' : '' ?>>
            <label class="form-check-label" for="mostPopular">Mais Populares</label>
          </div>
        </div>

        <!-- Filtro por Pesquisa -->
        <div class="mb-3">
          <h6>Pesquisa</h6>
          <form method="get" action="">
            <input type="text" class="form-control w-100" placeholder="Buscar livros..." name="search" id="searchBooks" value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" class="btn btn-primary mt-2">Buscar</button>
          </form>
        </div>

        <!-- Filtro Premium -->
        <div>
          <h6>Tipo de Acesso</h6>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="premium" id="premiumBooks">
            <label class="form-check-label" for="premiumBooks">Livros Premium</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="free" id="freeBooks">
            <label class="form-check-label" for="freeBooks">Livros Gratuitos</label>
          </div>
        </div>
      </aside>

      <!-- Livros -->
      <section class="col-md-10 col-sm-9 col-12">
        <div class="row">
          <?php if (!empty($books)): ?>
            <?php foreach ($books as $book): ?>
              <div class="col-6 col-sm-4 col-md-4 mb-4"> <!-- Agora 3 livros por linha em telas médias -->
                <div class="card h-100">
                  <img src="<?= htmlspecialchars($book['cover_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                    <p class="card-text"><?= substr(strip_tags($book['description']), 0, 200) ?>...</p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Nenhum livro encontrado para a categoria selecionada.</p>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>

  <!-- Footer -->
  <?php include 'footer.html'; ?>

  <!-- Bootstrap JS (opcional) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
