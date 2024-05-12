document.addEventListener("DOMContentLoaded", function () {
    fetchUserList();
    document
        .getElementById("addUserForm")
        .addEventListener("submit", function (event) {
            event.preventDefault();
            addUser();
        });
});

function fetchUserList() {
    fetch("/server/getUsers.php")
        .then((response) => response.json())
        .then((data) => {
            const userList = document.getElementById("userTableBody");
            userList.innerHTML = "";
            data.forEach((user) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                                <td>${user.user_id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.password}</td>
                                <td>${user.isAdmin ? "Sí" : "No"}</td>
                                <td>
                                        <button onclick="editUser(${user.user_id})">Editar</button>
                                        <button onclick="deleteUser(${
                                            user.user_id
                                        })">Eliminar</button>
                                </td>
                        `;
                userList.appendChild(row);
            });
        })
        .catch((error) => {
            console.error("Error al obtener la lista de usuarios:", error);
        });
}

function addUser() {
    const addUserForm = document.getElementById("addUserForm");
    const formData = new FormData(addUserForm);
    fetch("/server/addUser.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => {
            if (response.ok) {
                fetchUserList();
                addUserForm.reset();
            } else {
                console.error("Error al agregar el usuario.");
            }
        })
        .catch((error) => {
            console.error("Error al agregar el usuario:", error);
        });
}

function deleteUser(userId) {
    if (confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
        fetch(`/server/deleteUser.php?user_id=${userId}`, {
            method: "DELETE",
        })
            .then((response) => {
                if (response.ok) {
                    fetchUserList();
                } else {
                    console.error("Error al eliminar el usuario.");
                }
            })
            .catch((error) => {
                console.error("Error al eliminar el usuario:", error);
            });
    }
}

function editUser(userId) {
    const name = prompt("Ingresa el nuevo nombre:");
    const email = prompt("Ingresa el nuevo correo electrónico:");
    const password = prompt("Ingresa la nueva contraseña:");
    const isAdminString = prompt("¿Es este usuario un administrador? (true/false)");

    const isAdmin = isAdminString.toLowerCase() === "true";

    if (name !== null && email !== null && password !== null) {
        const formData = new FormData();
        formData.append("user_id", userId);
        formData.append("name", name);
        formData.append("email", email);
        formData.append("password", password);
        formData.append("is_admin", isAdmin ? 1 : 0);

        fetch("/server/editUser.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (response.ok) {
                    fetchUserList();
                } else {
                    console.error("Error al editar el usuario.");
                }
            })
            .catch((error) => {
                console.error("Error al editar el usuario:", error);
            });
    }
}
