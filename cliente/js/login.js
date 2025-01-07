document.getElementById('form-login').addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevenir recarga de página

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        // Enviar datos al servidor mediante POST
        const response = await fetch('http://localhost/ProyectoTiendaOnline/servidor/api/procesar.php?action=login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password }),
        });
        
        // Verificar si la respuesta es correcta
        if (!response.ok) {
            const errorData = await response.json();
            console.log(errorData); // Verifica el error en la consola
            throw new Error(errorData.error || 'Error en el servidor');
        }

        // Parsear la respuesta del servidor
        const data = await response.json();
        console.log('Respuesta del servidor:', data); // Ver la respuesta completa

        if (!data.token || !data.storeData) {
            throw new Error('Respuesta inválida del servidor');
        }

        // Guardar token y datos de la tienda en localStorage
        localStorage.setItem('token', data.token);
        localStorage.setItem('store', JSON.stringify(data.storeData));

        // Redirigir al dashboard
        window.location.href = 'dashboard.html';
    } catch (error) {
        // Mostrar mensaje de error
        alert(error.message); // Muestra el error
    }
});






