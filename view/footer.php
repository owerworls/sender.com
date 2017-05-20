<div id="dialogInvoice" title="Выписать счет">
    <br>
    Сумма:
    <form action="/" onsubmit=" getInvoice()" id="getInvoiceForm">
        <input class="form-control input-sm" id="invoiceSum" pattern="^[\d,.]+$"   title="Пример: 1000.00"  required autocomplete="off" style="margin: 0 0 6px ">
        <input type="submit" class="btn btn-primary btn-sm btn-block" id="invoiceOk" >
    </form>
</div>

</body>

</html>
