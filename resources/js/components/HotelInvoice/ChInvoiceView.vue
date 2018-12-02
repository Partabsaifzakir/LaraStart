<style>
  @media print{
  .nav.nav-tabs li:not(.active){
    display: none;
  }
  .invoice{
    padding: 10px 20px;
  }
  .invoice{
    background-position: center center;
    background-repeat: no-repeat;
  }
  .table-responsive table{
    background-color: transparent !important;
  }
}

@page{
  margin: 0;
  margin-top: 20px;
  
  }

@media screen
{
 .noScreen{display:none;}
}

@media print
{
    .noScreen{display:none;}
}

textarea.ModTD{
  white-space:pre-wrap;
  border: none;
  background: #f2f2f2;
}
.ModTD::first-line {
    font-weight: bold;
    text-transform: uppercase;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 50%;
    border: 1px solid #ddd;
}

th, td {
    text-align: left;
    padding: 8px;
}

</style>

<template>
  <div class="invoice p-3 mb-3" id="mydiv">
    <!-- ========INVOICE HEADER======== -->
    <div class="row">
      <div class="col-3">
        <span class="brand-text font-weight-light">
          <img src="/img/globe.png" alt="Invoice Logo">
        </span>
        <br>
        <small>NTN: 0572571-2</small>
      </div>
      <div style="text-align: center;" class="col-6">
        <span class="brand-text font-weight-light">
          <img src="/img/name.png" alt="Invoice Logo">
        </span>
        <br>
        <br>
        <p>271 Syedna Ismail Road, Shabbirabad, Karachi.
          <br>Telephone: 0213-2419090 Cell: 0300-2037789
          <br>Email: abbasali54@hotmail.com
        </p>
      </div>
      <div class="col-3"></div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- ========CUSTOMER INFO======== -->
    <div class="row invoice-info">
      <div class="col-sm-5 invoice-col">
        <address>
          <strong>Customer Info</strong>
          <br>
          <b>Name:</b>
          <span>{{form.customer.customer_name}}</span>
          <br>
          <b>Address:</b>
          <span>{{form.customer.customer_office_address}}</span>
          <br>
          <b>Phone:</b>
          <span>{{form.customer.customer_contact}}</span>
          <br>
          <b>Email:</b>
          <span>{{form.customer.customer_email}}</span>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-2 invoice-col" style="text-align: center; margin-top: 90px;"><h2>INVOICE</h2></div>
      <!-- /.col -->
      <div class="col-sm-5 invoice-col" style="text-align: center;">
        <b>&nbsp;&nbsp;&nbsp;Invoice No:</b>
        <span>{{form.ch_invoice_no}}</span>
        <br>
        <b>Invoice Date:</b>
        <span>{{form.ch_invoice_date | myDate }}</span>
        <br>
      </div>
      <!-- /.col -->
    </div>

    <!-- /.row -->
    <!-- ========INVOICE ITEMS======== -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Description</th>
              <th>Fare</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(chInvoiceItem, key) in form.ch_invoice_items" :key="key">
              <td><textarea class="ModTD" cols="100" rows="5" disabled v-model="chInvoiceItem.ch_description"></textarea></td>
              <td>{{formatPrice(chInvoiceItem.ch_fares)}}</td>
              <td>{{formatPrice(chInvoiceItem.ch_sub_total)}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <br>
    <br>
    <!-- /.row -->
    <hr>
    <div class="row">
      <!-- accepted payments column -->
      <div class="col-8">
        <p class="lead">
          <small class="text-muted well well-sm no-shadow">
            <strong>Amount in words:</strong>
            <span style="text-transform: capitalize;">{{form.ch_invoice_grand_total | toWords}} Rupees Only.</span>
          </small>
        </p>
      </div>

      <!-- /.col -->
      <div class="col-4 float-right">
        <div class="table-responsive">
          <table class="table">
            <tbody>
              <tr>
                <th>Total Fares</th>
                <td>{{formatPrice(form.ch_invoice_fares_total)}}</td>
              </tr>
              <tr>
                <th>Grand Total</th>
                <td>{{formatPrice(form.ch_invoice_grand_total)}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12">
        <div class="table-responsive">
          <footer class="text-muted well no-shadow">
            <p>As per Finance Bill 2007, Travel Agents are exempted for deduction of Tax on Sale of Tickets and there is no need<br> to submit Exemption Certificate.
              [Section 153(1) &amp; Clause 43(B) of Part IV of Second Schedule]
            </p>
            <br>
            <br>
            <p>
              <strong>THIS IS SYSTEM GENERATED INVOICE AND IT DOES NOT CARRY ANY SIGNATURE.</strong>
            </p>
          </footer>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <!-- this row will not appear when printing -->
    <div class="no-print card-footer" data-html2canvas-ignore="true" style="background-color: lightgray;">
      <div class="col-12">
        <a href="#" @click.prevent="printMe" target="_blank" class="btn btn-default float-right">
          <i class="fa fa-print"></i> Print
        </a>
        <router-link to="/ch-invoices" class="btn btn-success float-left">
          <i class="fas fa-backspace"></i> Back
        </router-link>
        <a href="#" @click.prevent="createPDF" class="btn btn-primary float-right" style="margin-right: 5px;">
          <i class="fa fa-download"></i> Generate PDF
        </a>
      </div>
    </div>
  </div>
</template>


<script>
import html2pdf from 'html2pdf.js'

export default {
  data() {
    return {
      chInvoices: {},
      id: {},
      customers: null,
      form: new Form({
        id: "",
        customer_id: "",
        ch_invoice_no: "",
        ch_invoice_date: "",
        ch_invoice_fares_total: "",
        ch_invoice_grand_total: "",

        chInvoiceItems: [
          {
            id: "",
            ch_invoice_id: "",
            ch_description: "",
            ch_fares: "",
            ch_sub_total: ""
          }
        ]
      })
    };
  },
  methods: {
  createPDF() {
    var element = document.getElementById('mydiv');

    var opt = {
      margin:       0,
      filename:     'myfile.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2 },
      jsPDF:        { unit: 'mm', format: [280, 350], orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save();
    },

  printMe() {
    window.print();
    },

  formatPrice(value) {
      let val = (value / 1).toFixed().replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); //75.674,00
    },
  loadCustomers() {
    axios
      .get("/api/customer")
      .then(({ data }) => (this.customers = data.data));
  },
  loadChInvoices() {
    axios
      .get("/api/ch-invoice")
      .then(({ data }) => (this.chInvoices = data.data));
  }
  },
  created: function() {
    axios
      .get("/api/ch-invoice/" + this.$route.params.id)
      .then(({ data }) => {
        console.log(data);
        this.form = new Form(data);
      })
      .catch(error => {
        console.log(error.response);
      });
  },
  mounted() {
    this.loadChInvoices();
    Fire.$on("RefreshTable", () => {
      this.loadChInvoices();
    });
    this.loadCustomers();
    Fire.$on("RefreshTable", () => {
      this.loadCustomers();
    });
  }
};
</script>
