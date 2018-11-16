<template>
    <div class="container">
<div class="invoice p-3 mb-3">
              <!-- ========INVOICE HEADER======== -->
              <div class="row">
                <div class="col-3">
                  <span class="brand-text font-weight-light"><img src="/img/globe.png" alt="Invoice Logo"></span><br>
                  <small>Govt. License No. 1579<br>NTN  STN 2541235-8 </small>
                </div>
                <div style="text-align: center;" class="col-6">
                  <span class="brand-text font-weight-light"><img src="/img/name.png" alt="Invoice Logo"></span>
                  <p>102-105, Muhammad House, I.I Chundigar Road, Karachi. <br>Phone: 0213-4567895, 0213-5478965, 0214-5987456 Fax: 0213-5478965 <br>Email: alitravel@alitravel.com</p>
                </div>
                <div class="col-3"></div>
              </div><br><br>

              <!-- ========CUSTOMER INFO======== -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col" >
                  <address>
                    <strong>Customer Info</strong><br>
                    <b>Name:</b> <span>{{form.customer.customer_name}}</span><br>
                    <b>Address:</b> <span>{{form.customer.customer_office_address}}</span><br>
                    <b>Phone:</b> <span>{{form.customer.customer_contact}}</span><br>
                    <b>Email:</b> <span>{{form.customer.customer_email}}</span>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Invoice No:</b> <span>{{form.ct_invoice_no}}</span><br>
                  <b>Invoice Date:</b> <span>{{form.ct_invoice_date | myDate }}</span><br>
                </div>
                <!-- /.col -->
              </div><br><br>
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
              </div><br><br>
              <!-- /.row -->
              <hr>
              <div class="row">
                <!-- accepted payments column -->
                <div class="col-8">
                  <p class="lead">Taxes Break Up</p>
                  <small class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    <strong>Tax SB:</strong><span> {{formatPrice(form.ct_invoice_total_tax_SB)}} </span>
                    <strong>Tax SRP:</strong><span> {{formatPrice(form.ct_invoice_total_tax_SRP)}} </span>
                    <strong>Tax YQ:</strong> <span> {{formatPrice(form.ct_invoice_total_tax_YQ)}} </span>
                    <strong>Tax RG:</strong><span> {{formatPrice(form.ct_invoice_total_tax_RG)}} </span>
                    <strong>Tax PK:</strong><span> {{formatPrice(form.ct_invoice_total_tax_PK)}} </span>
                    <strong>Tax YR:</strong><span> {{formatPrice(form.ct_invoice_total_tax_YR)}} </span><br>
                    <strong>Tax SF:</strong> <span> {{formatPrice(form.ct_invoice_total_tax_SF)}} </span> 
                    <strong>Tax PTT:</strong><span> {{formatPrice(form.ct_invoice_total_tax_PTT)}} </span>
                    <strong>Tax OAS:</strong><span> {{formatPrice(form.ct_invoice_total_tax_OAS)}} </span> 
                    <strong>Tax PSF:</strong><span> {{formatPrice(form.ct_invoice_total_tax_PSF)}} </span> 
                    <strong>Tax PB:</strong> <span> {{formatPrice(form.ct_invoice_total_tax_PB)}} </span> 
                    <strong>Tax OAD:</strong><span> {{formatPrice(form.ct_invoice_total_tax_OAD)}} </span>
                  </small>
                  <p class="lead">
                    <small class="text-muted well well-sm no-shadow">
                      <strong>Amount in words:</strong> <span>Two Thousand Only</span>
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
                    </tbody></table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="#" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                  <router-link to="/ct-invoices" class="btn btn-success float-right"><i class="fas fa-backspace"></i> Back
                  </router-link>
                  <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fa fa-download"></i> Generate PDF
                  </button>
                </div>
              </div>
            </div>
    </div>
</template>

<script>
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
