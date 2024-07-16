<?php
include 'db.php';

// Función para leer usuarios
function readUsers($conn)
{
    $sql = "SELECT id, nombre, apellido, correo FROM usuarios";
    $result = $conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Crear usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'create') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $sql = "INSERT INTO usuarios (nombre, apellido, correo) VALUES ('$nombre', '$apellido', '$correo')";
    $conn->query($sql);
}

// Actualizar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', correo='$correo' WHERE id=$id";
    $conn->query($sql);
}

// Eliminar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];
    $sql = "DELETE FROM usuarios WHERE id=$id";
    $conn->query($sql);
}

$users = readUsers($conn);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="text-center alert alert-success mt-2">
            <?php echo "Fabían Hermosilla - Gestión de Proyectos de Software - DevOps"; ?>
        </div>

        <!-- Tabla de usuarios -->
        <div class="row mt-2 mb-3">
            <div class="col">
                <h3>Lista de Usuarios</h3>
            </div>
            <div class="col text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Crear Usuario</button>
            </div>
        </div>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['nombre']; ?></td>
                        <td><?php echo $user['apellido']; ?></td>
                        <td><?php echo $user['correo']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal" onclick="fillUpdateForm(<?php echo $user['id']; ?>, '<?php echo $user['nombre']; ?>', '<?php echo $user['apellido']; ?>', '<?php echo $user['correo']; ?>')">Actualizar</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="document.getElementById('deleteId').value=<?php echo $user['id']; ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Crear Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        Nombre: <input type="text" name="nombre" class="form-control"><br>
                        Apellido: <input type="text" name="apellido" class="form-control"><br>
                        Correo: <input type="email" name="correo" class="form-control"><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <input type="submit" value="Crear" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Actualizar Usuario -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Actualizar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="post" id="updateForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        ID: <input type="text" name="id" id="updateId" class="form-control" readonly><br>
                        Nombre: <input type="text" name="nombre" id="updateNombre" class="form-control"><br>
                        Apellido: <input type="text" name="apellido" id="updateApellido" class="form-control"><br>
                        Correo: <input type="email" name="correo" id="updateCorreo" class="form-control"><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <input type="submit" value="Actualizar" class="btn btn-warning">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Usuario -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Eliminar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <p>¿Estás seguro de que deseas eliminar este usuario?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <input type="submit" value="Eliminar" class="btn btn-danger">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fillUpdateForm(id, nombre, apellido, correo) {
            document.getElementById('updateId').value = id;
            document.getElementById('updateNombre').value = nombre;
            document.getElementById('updateApellido').value = apellido;
            document.getElementById('updateCorreo').value = correo;
        }
    </script>
</body>

</html>

<?php $conn->close(); ?>
