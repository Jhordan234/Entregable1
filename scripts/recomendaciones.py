import sys
import json
import mysql.connector

# Obtener el género y convertirlo a minúsculas
genero = sys.argv[1].lower() if len(sys.argv) > 1 else "shooter"

# Conectar a la base de datos
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="tienda_videojuegos"
)
cursor = conn.cursor(dictionary=True)

# Obtener juegos del género solicitado
cursor.execute("SELECT * FROM videojuegos WHERE LOWER(genero) = %s ORDER BY RAND() LIMIT 6", (genero,))
recomendaciones = cursor.fetchall()

# Cerrar conexión
cursor.close()
conn.close()

# Convertir a JSON manejando Decimals
print(json.dumps(recomendaciones, default=str))  