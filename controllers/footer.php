
</div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function loadPage(url) {
            var holdrequest = new XMLHttpRequest();
            holdrequest.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("main-content").innerHTML = this.responseText;
                }
            }
            holdrequest.open("POST", url, true);
            holdrequest.send();
        }
    </script>
</body>
</html>
