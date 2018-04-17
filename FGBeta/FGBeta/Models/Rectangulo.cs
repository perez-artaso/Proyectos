using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace FGBeta.Models
{
    public class Rectangulo
    {
        #region Atributos
        double rectBase;
        double rectAltura;
        #endregion

        #region Constructores
        public Rectangulo()
        {
            this.Base = 0;
            this.Altura = 0;
        }
        public Rectangulo(double rectBase, double rectAltura)
        {
            if(rectBase<0)
            {
                rectBase *= -1;
            }
            if (rectAltura < 0)
            {
                rectAltura *= -1;
            }
            this.Altura = rectAltura;
            this.Base = rectBase;
        }
        #endregion

        #region Propiedades
        public double Base
        {
            get
            {
                return this.rectBase;
            }
            set
            {
                this.rectBase = value;
            }
        }
        public double Altura
        {
            get
            {
                return this.rectAltura;
            }
            set
            {
                this.rectAltura = value;
            }
        }
        public double Perimetro
        {
            get
            {
                return this.CalcularPerimetro();
            }
        }
        public double Area
        {
            get
            {
                return this.CalcularArea();
            }
        }
        #endregion

        #region Métodos
        double CalcularPerimetro()
        {
            return (this.Base + this.Altura) * 2;
        }
        double CalcularArea()
        {
            return this.Base * this.Altura;
        }
        #endregion
    }
}