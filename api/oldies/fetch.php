<script>
fetch("http://localhost/momento/api/api_categories.php?category=wedding&page=1", {
    method: "GET",
    headers: {
        "X-API-Key": "335aa9ec70256599bab3a1ed3c712c044f1bce4e8de8703f77256d958ee53ea1"
    }
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error(error));
</script>
