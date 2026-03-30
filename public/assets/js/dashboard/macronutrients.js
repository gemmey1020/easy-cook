document.addEventListener('DOMContentLoaded', function () {

      const data = {
        carbs: 50,
        protein: 20,
        cal: 10,
        fat: 20
      };

      createDonutChart(data);

      // ------------------------------------------------------------
      function createDonutChart(rawData) {
        let totalValue = 0;
        for (let key in rawData) {
          totalValue += rawData[key];
        }
        if (totalValue === 0) {
          console.warn('Total value is zero.');
          return;
        }

        const colorMap = {
          carbs: '#3498db',
          protein: '#2ecc71',
          cal: '#e67e22',
          fat: '#e74c3c'
        };

        const items = [];
        for (let key in rawData) {
          const rawValue = rawData[key];
          const percentage = (rawValue / totalValue) * 100;
          items.push({
            key: key,
            value: rawValue,
            percentage: percentage,
            color: colorMap[key]
          });
        }

        let cumulativePerc = 0;
        const gradientParts = items.map(item => {
          const startPerc = cumulativePerc;
          const endPerc = cumulativePerc + item.percentage;
          cumulativePerc = endPerc;
          return `${item.color} ${startPerc}% ${endPerc}%`;
        });
        const fullGradient = `conic-gradient(${gradientParts.join(', ')})`;

        const donutEl = document.getElementById('myDonutChart');
        donutEl.style.background = fullGradient;

        const legendEl = document.getElementById('myLegend');
        legendEl.innerHTML = '';

        items.forEach(item => {
          const li = document.createElement('li');
          li.classList.add(item.key);

          const colorSpan = document.createElement('span');
          colorSpan.classList.add('legend-color');

          const textNode = document.createTextNode(' ' + capitalizeFirstLetter(item.key));
          const percSpan = document.createElement('span');
          percSpan.classList.add('legend-perc');
          percSpan.textContent = item.percentage.toFixed(1) + '%';

          li.appendChild(colorSpan);
          li.appendChild(textNode);
          li.appendChild(percSpan);
          legendEl.appendChild(li);
        });
      }

      function capitalizeFirstLetter(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
      }
    });
