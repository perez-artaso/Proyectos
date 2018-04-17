using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using FGBeta.Models;

namespace FGBeta.Controllers
{
    public class FormasController : Controller
    {
        // GET: Formas
        [HttpPost]
        public ActionResult Circulo()
        {
            Circulo circulo = new Circulo(Convert.ToDouble(Request["radio"]));
            return View(circulo);
        }
        [HttpPost]
        public ActionResult Rectangulo()
        {
            Rectangulo rectangulo = new Rectangulo(Convert.ToDouble(Request["base"]), Convert.ToDouble(Request["altura"]));
            return View(rectangulo);
        }

        [HttpPost]
        public ActionResult Triangulo()
        {
            ConstructorTriangulos triangulo = new ConstructorTriangulos(Convert.ToDouble(Request["ladoA"]), Convert.ToDouble(Request["ladoB"]), Convert.ToDouble(Request["ladoC"]));
            return View(triangulo);
        }
    }
}