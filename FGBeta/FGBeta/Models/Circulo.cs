using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;


namespace FGBeta.Models
{
    public class Circulo
    {
        #region Atributos
        double radio;
        #endregion

        #region Constructores

        public Circulo()
        {
            this.Radio = 0;
        }

        public Circulo(double radio)
        {
            if(radio < 0)
            {
                radio = radio * -1;
            }
            this.Radio = radio;
        }

        #endregion

        #region Propiedades
        public double Radio
        {
            get
            {
                return this.radio;
            }
            set
            {
                this.radio = value;
            }
        }
        public double Area
        {
            get
            {
                return this.CalcularArea();
            }
        }
        public double Perimetro
        {
            get
            {
                return this.CalcularPerimetro();
            }
        }
        #endregion

        #region Métodos
        double CalcularPerimetro()
        {
            return 2 * Math.PI * this.Radio;
        }
        double CalcularArea()
        {
            return Math.PI * Math.Pow(this.Radio, 2);
        }
        #endregion
    }
}