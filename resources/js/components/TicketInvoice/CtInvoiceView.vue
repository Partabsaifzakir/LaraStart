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
        <p>271 Syedna Ismail Road, Shabbirabad, Karachi East Jamshed Road.
          <br>Telephone: 0213-2419090 Cell: 0300-2037789
          <br>Email: abbasali54@hotmail.com
        </p>
      </div>
      <div class="col-3"></div>
    </div>
    <br>
    <br>

    <!-- ========CUSTOMER INFO======== -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
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
      <div class="col-sm-4 invoice-col"></div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Invoice No:</b>
        <span>{{form.ct_invoice_no}}</span>
        <br>
        <b>Invoice Date:</b>
        <span>{{form.ct_invoice_date | myDate }}</span>
        <br>
      </div>
      <!-- /.col -->
    </div>
    <br>
    <br>
    <!-- /.row -->
    <!-- ========INVOICE ITEMS======== -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Passenger Name</th>
              <th>Ticket No</th>
              <th>Flight No</th>
              <th>Departure Date</th>
              <th>Sector</th>
              <th>Taxes</th>
              <th>Fare</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(ctInvoiceItem, key) in form.ct_invoice_items" :key="key">
              <td>{{ctInvoiceItem.ct_passenger_name}}</td>
              <td>{{ctInvoiceItem.ct_ticket_no}}</td>
              <td>{{ctInvoiceItem.ct_flight_no}}</td>
              <td>{{ctInvoiceItem.ct_departure_date}}</td>
              <td>{{ctInvoiceItem.ct_sector}}</td>
              <td>PKR {{formatPrice(ctInvoiceItem.ct_total_tax_breakup)}}</td>
              <td>PKR {{formatPrice(ctInvoiceItem.ct_fares)}}</td>
              <td>PKR {{formatPrice(ctInvoiceItem.ct_sub_total)}}</td>
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
        <p class="lead">Taxes Break Up</p>
        <small class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          <strong>Tax SB:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_SB)}}</span>
          <strong>Tax SRP:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_SRP)}}</span>
          <strong>Tax YQ:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_YQ)}}</span>
          <strong>Tax RG:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_RG)}}</span>
          <strong>Tax PK:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_PK)}}</span>
          <strong>Tax YR:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_YR)}}</span>
          <br>
          <strong>Tax SF:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_SF)}}</span>
          <strong>Tax PTT:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_PTT)}}</span>
          <strong>Tax OAS:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_OAS)}}</span>
          <strong>Tax PSF:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_PSF)}}</span>
          <strong>Tax PB:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_PB)}}</span>
          <strong>Tax OAD:</strong>
          <span>{{formatPrice(form.ct_invoice_total_tax_OAD)}}</span>
        </small>
        <p class="lead">
          <small class="text-muted well well-sm no-shadow">
            <strong>Amount in words:</strong>
            <span>{{form.ct_invoice_grand_total | toWords | capitalize}} rupees only.</span>
          </small>
        </p>
      </div>

      <!-- /.col -->
      <div class="col-4 float-right">
        <div class="table-responsive">
          <table class="table">
            <tbody>
              <tr>
                <th style="width:50%">Total Taxes</th>
                <td>PKR {{formatPrice(form.ct_invoice_taxes_grand_total)}}</td>
              </tr>
              <tr>
                <th>Total Fares</th>
                <td>PKR {{formatPrice(form.ct_invoice_fares_total)}}</td>
              </tr>
              <tr>
                <th>Grand Total</th>
                <td>PKR {{formatPrice(form.ct_invoice_grand_total)}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-12">
        <div class="table-responsive">
          <footer class="text-muted well well-sm no-shadow">
            <small>As per Finance Bill 2007, Travel Agents are exempted for deduction of Tax on Sale of Tickets and there is no need to submit Exemption Certificate.
              <br>[Section 153(1) &amp; Clause 43(B) of Part IV of Second Schedule]
            </small>
            <br>
            <br>
            <small>
              <strong>THIS IS SYSTEM GENERATED INVOICE AND IT DOES NOT CARRY ANY SIGNATURE.</strong>
            </small>
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
        <router-link to="/ct-invoices" class="btn btn-success float-left">
          <i class="fas fa-backspace"></i> Back
        </router-link>
        <a
          href="#"
          @click.prevent="createPDF"
          class="btn btn-primary float-right"
          style="margin-right: 5px;"
        >
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
      ctInvoices: {},
      id: {},
      customers: null,
      form: new Form({
        id: "",
        customer_id: "",
        ct_invoice_no: "",
        ct_invoice_date: "",
        ct_invoice_fares_total: "",
        ct_invoice_taxes_grand_total: "",
        ct_invoice_grand_total: "",
        ct_invoice_grand_total_words: "",
        ct_invoice_terms: "",

        ct_invoice_total_tax_SB: 0,
        ct_invoice_total_tax_SRP: 0,
        ct_invoice_total_tax_YQ: 0,
        ct_invoice_total_tax_RG: 0,
        ct_invoice_total_tax_PK: 0,
        ct_invoice_total_tax_YR: 0,
        ct_invoice_total_tax_SF: 0,
        ct_invoice_total_tax_PTT: 0,
        ct_invoice_total_tax_OAS: 0,
        ct_invoice_total_tax_PSF: 0,
        ct_invoice_total_tax_PB: 0,
        ct_invoice_total_tax_OAD: 0,

        ctInvoiceItems: [
          {
            id: "",
            ct_invoice_id: "",
            ct_passenger_name: "",
            ct_ticket_no: "",
            ct_flight_no: "",
            ct_departure_date: "",
            ct_sector: "",
            ct_tax_SB: "",
            ct_tax_SRP: "",
            ct_tax_YQ: "",
            ct_tax_RG: "",
            ct_tax_PK: "",
            ct_tax_YR: "",
            ct_tax_SF: "",
            ct_tax_PTT: "",
            ct_tax_OAS: "",
            ct_tax_PSF: "",
            ct_tax_PB: "",
            ct_tax_OAD: "",
            ct_fares: "",
            ct_total_tax_breakup: "",
            ct_sub_total: ""
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
  loadCtInvoices() {
    axios
      .get("/api/ct-invoice")
      .then(({ data }) => (this.ctInvoices = data.data));
  }
  },
  created: function() {
    axios
      .get("/api/ct-invoice/" + this.$route.params.id)
      .then(({ data }) => {
        console.log(data);
        this.form = new Form(data);
      })
      .catch(error => {
        console.log(error.response);
      });
  },
  mounted() {
    this.loadCtInvoices();
    Fire.$on("RefreshTable", () => {
      this.loadCtInvoices();
    });
    this.loadCustomers();
    Fire.$on("RefreshTable", () => {
      this.loadCustomers();
    });
  }
};
</script>
