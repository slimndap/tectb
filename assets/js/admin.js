"use strict";(function(){var t;t=function(){var n,e,i,c;if(!(1>(e=jQuery("#tectb-admin-form")).length))return n=e.find("#tectb-admin-add-price"),c=e.find("#tectb-admin-prices-table"),i=c.find("tbody tr"),c.toggleClass("has-prices",0<i.length),i.each((function(){var n;return(n=jQuery(this)).find("button").unbind().click((function(){return n.remove(),t()}))})),n.unbind().click((function(){var n;return n=i.length,console.log(n),c.find("tbody").append('<tr> <td> <input type="number" name="tickets_button_prices['.concat(n,'][amount]"  /> </td> <td> <input type="text" name="tickets_button_prices[').concat(n,'][name]" /> </td> <td> <button><span class="dashicons dashicons-trash"></span></button> </td> </tr>')),t()}))},jQuery((function(){return t()}))}).call(void 0);