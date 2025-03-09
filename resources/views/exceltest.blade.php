<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >
        <meta
            http-equiv="X-UA-Compatible"
            content="ie=edge"
        >
        <title>Excel</title>
    </head>

    <body>
        <form
            action="/excel_import"
            method="post"
            enctype="multipart/form-data"
        >
            @csrf
            <div class="mb-3">
                <label
                    for="file"
                    class="form-label"
                >Choose Excel File</label>
                <input
                    type="file"
                    class="form-control"
                    name="file"
                    id="file"
                    accept=".xlsx,.xls"
                    required
                >
            </div>
            <button
                type="submit"
                class="btn btn-primary"
            >Import</button>
        </form>
    </body>

</html>
