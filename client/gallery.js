fetch("../server/getUserName.php")
.then(response => response.json())
.then(data => {
    const userName = data.name;
    if (userName) {
        document.getElementById("userName").textContent = userName;
    }
})
.catch(error => {
    console.error("Error al obtener el nombre del usuario:", error);
});

document.getElementById("uploadBtn").addEventListener("click", function() {
    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.accept = "image/*";
    fileInput.click();

    fileInput.addEventListener("change", function() {
        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append("image", file);

        fetch("../server/upload.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("¡Imagen subida exitosamente!");
                fetchGallery();
            } else {
                alert("¡Error al subir la imagen!");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Ocurrió un error al subir la imagen.");
        });
    });
});

function fetchGallery() {
    fetch("../server/getGallery.php")
    .then(response => response.json())
    .then(data => {
        document.getElementById("gallery").innerHTML = "";

        data.forEach(image => {
            const imgElement = document.createElement("img");
            imgElement.src = image.path;
            imgElement.alt = image.userName;
            imgElement.addEventListener("click", function() {
                if (confirm("¿Estás seguro de que quieres eliminar esta imagen?")) {
                    deleteImage(image.id);
                }
            });

            const galleryItem = document.createElement("div");
            galleryItem.classList.add("gallery-item");
            galleryItem.appendChild(imgElement);
            document.getElementById("gallery").appendChild(galleryItem);
        });
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Ocurrió un error al obtener la galería.");
    });
}

function deleteImage(imageId) {
    fetch("../server/deleteImage.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: imageId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("¡Imagen eliminada exitosamente!");
            fetchGallery();
        } else {
            alert("¡Error al eliminar la imagen!");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Ocurrió un error al eliminar la imagen.");
    });
}

fetchGallery();
